<?php namespace App\Data\Faq;

use App\Services\LanguageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Event;
use Auth;
use DB;

use App\Events\FollowingUserPostNotificationHandler;
use App\Data\Blog\Category;
use App\Services\PostService;
use App\Services\GroupService;
use App\Data\System\User;
use Cache;

class Question extends Model
{
    CONST REQUEST_CLASS = ' answer-user-request';
    //requested for this question
    CONST REQUESTED_CLASS = ' answer-user-request sent';

    CONST LIMIT_USER_REQUEST = 4;

    use SoftDeletes;
    const LIMIT_LATEST_QUESTION = 10;

    protected $table = 'questions';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'language_code',
        'number_helpful',
        'number_clip',
        'number_view',
        'number_answer',
        'blocked',
        'user_id'
    ];

    protected $dates = [
        'published_at',
        'deleted_at'
    ];

    /**
     * Answers
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Categories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'question_category');
    }

    /**
     * Clips
     */
    public function clips()
    {
        return $this->hasMany(Clip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check whether the post is published or not
     */
    public function isPublished()
    {
        return $this->published_at !== null;
    }

    /**
     * get comment or request ask author for question
     */

    public function requestDetailQuestions()
    {
        return $this->hasMany('App\Data\Faq\RequestDetailQuestion');
    }

    /**
     * get answer detail questions
     */
    public function answerDetailQuestions()
    {
        return $this->hasManyThrough('App\Data\Faq\RequestDetailQuestion', 'App\Data\Faq\AnswerDetailQuestion');
    }

    /**
     * get answer detail questions
     */
    public function requestUserAnsers()
    {
        return $this->hasMany('App\Data\Faq\RequestUserAnswer');
    }

    /**
     * insert or update question to database
     * @param  array
     * @return array        message and encryted_id (this is id field of question)
     */
    public static function insertDB($input)
    {
        $question = Question::firstOrNew(['id' => $input['encrypted_id']]);
        $question->title = $input['title'];
        $question->slug = $input['slug'];
        $question->content = $input['content'];
        $question->published_at = (isset($input['publish']) ? date('Y-m-d H:i:s') : null);
        $question->language_code = $input['language_code'];
        $question->user_id = $input['user_id'];

        if ($question->save() && $input['category'] != 'Uncategory') {

            $categoriesId = PostService::parseCategoriesId($input['category']);
            if (!empty($categoriesId)) {
                $question->categories()->sync($categoriesId);
            }
            //fire notification for follow user
            if ($question->isPublished()) {
                Event::fire(FollowingUserPostNotificationHandler::EVENT_NAME, $question);
            }
            return [
                'encrypted_id' => $question->id,
                'saved_time' => date('H:i:s', strtotime($question->updated_at)),
                'saved' => true,
            ];
        }

        return [
            'encrypted_id' => 0,
            'saved_time' => '',
            'saved' => false,
        ];

    }

    /**
     * get all data for display.
     * @return array
     */
    public function getViewData()
    {
        $questionViewData = [];
        $questionViewData['qContent'] = $this->content;
        $questionViewData['qTitle'] = $this->title;
        $questionViewData['qId'] = $this->id;
        $questionViewData['qSlug'] = $this->slug;
        $questionViewData['solved'] = $this->getStatusSolved();
        $questionViewData['numberAnswer'] = $this->number_answer;
        $questionViewData['numberClip'] = $this->number_clip;
        $questionViewData['numberView'] = $this->number_view;
        $questionViewData['published'] = (!is_null($this->published_at) ? $this->published_at->format('F d, Y') : '');
        $questionViewData['qAuthor'] = $this->getAuthor();
        $questionViewData['categories'] = $this->categories()->get(['id', 'name', 'short_name']);
        $questionViewData['qRequestLink'] = $this->getReplyLink($this->user->id, '', trans('question.request_more'), false);
        //get request detail for question
        $requestDetail = $this->getRequestDetail();
        $questionViewData = array_merge($questionViewData, $requestDetail);
        $questionViewData['answers'] = $this->getAnswer();
        $questionViewData['userRecommends'] = $this->getUserRecommends();
        $questionViewData['userFollowing'] = $this->getUserFollowing();
        $questionViewData['userInGroup'] = $this->getUserGroup();
        return $questionViewData;
    }

    private function getReplyLink($author_id, $strClass = '', $label = 'button', $isAuthor = false)
    {
        $reply_link = '';
        if (Auth::user()->id == $author_id && $isAuthor) {
            $reply_link = '<button' . ' ' . $strClass . '>' . $label . '</button>';
        }

        return $reply_link;
    }

    /**
     * get time create request detail
     * @return string
     */

    public static function getTime($time)
    {
        Carbon::setLocale(\App::getLocale());
        return Carbon::createFromTimeStamp(strtotime($time))->diffForHumans();
    }

    /**
     * each question have many request detail by other user
     * @return array list request detail for question
     */
    private function getRequestDetail()
    {
        //request detail question
        $requestDetail = [];
        $requests = $this->requestDetailQuestions()->orderBy('request_detail_questions.created_at', 'DESC')
            ->with([
                'user',
                'answerDetailQuestions'
            ])->get();
        foreach ($requests as $rKey => $request) {
            //get data for request detail question
            $requestDetail['requestDetail'][$rKey]['requestDetailContent'] = $request->content;
            $requestDetail['requestDetail'][$rKey]['requestDetailUserId'] = $request->user_id;
            $requestDetail['requestDetail'][$rKey]['requestDetailAccName'] = $request->user->username;
            $requestDetail['requestDetail'][$rKey]['requestDetailUserName'] = $request->user->name;
            $requestDetail['requestDetail'][$rKey]['requestDetailCreatedAt'] = $this->getTime($request->created_at);
            $requestDetail['requestDetail'][$rKey]['replyBtnLink'] = $this->getReplyLink($this->user->id, 'class="btn-reply-request"', trans('question.reply'), true);
            //get answer detai for request detail question
            $requestDetail['requestDetail'][$rKey]['answers'] = [];
            foreach ($request->answerDetailQuestions as $aKey => $answer) {
                $requestDetail['requestDetail'][$rKey]['answers'][$aKey]['aContent'] = $answer['content'];
                $requestDetail['requestDetail'][$rKey]['answers'][$aKey]['answeredAt'] = $this->getTime($answer['created_at']);
            };

        }

        return $requestDetail;
    }

    /**
     * get all user without author who created a question
     * @param  integer $skip start point for get record
     * @param  int $limit limit number of record
     * @return array         list user recomend
     */
    private function getUserRecommends($skip = 0, $limit = Question::LIMIT_USER_REQUEST)
    {
        $users = User::where('id', '!=', $this->user_id)->orderBy('total_best_answer', 'DESC')
            ->orderBy('total_helpful_answer', 'DESC')->skip($skip)->take($limit)->get();
       return $this->getRequestInfo($users);
    }

    /**
     * get class for user requested or not
     * @param  int $user_id id of user
     * @return string class of request button
     */
    private function markUserRequested($user_id)
    {
        $uRequest = $this->requestUserAnsers()->where('user_id', $user_id)->first();
        if ($uRequest) {
            return Question::REQUESTED_CLASS;
        }

        return Question::REQUEST_CLASS;
    }

    /**
     * get answer for question
     * @return collection list answer for question
     */
    private function getAnswer()
    {
        return $this->answers()->where('parent', '=', 0)
            ->orderBy('best_answer', 'DESC')
            ->orderBy('number_helpful', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->with(['subQuestion', 'user'])->get();
    }

    /**
     * get info user request
     * @param $users list user
     * @return array
     */
    private function getRequestInfo($users)
    {
        $userRequests = [];
        foreach ($users as $key => $user) {
            $userRequests[$key]['username'] = $user->username;
            $userRequests[$key]['name'] = $user->name;
            $userRequests[$key]['avatar'] = user_img_url($user);
            $userRequests[$key]['best_answer'] = $user->total_best_answer;
            $userRequests[$key]['helpful'] = $user->total_helpful_answer;
            $userRequests[$key]['request_class'] = $this->markUserRequested($user->id);
        }
        return $userRequests;
    }

    /**
     * get all user following current login without author, who created a question
     * @param  integer $skip start point for get record
     * @param  int $limit limit number of record
     * @return array         list user following
     */
    private function getUserFollowing($skip = 0, $limit = Question::LIMIT_USER_REQUEST)
    {
        $userFollowing = [];
        if (Auth::check()) {
            $users = [];
            $follows = Auth::user()->following()->skip($skip)->take($limit)->get();
            foreach ($follows as $following) {
                $users[] = $following->follower;
            }
            $userFollowing = $this->getRequestInfo($users);
        }
        return $userFollowing;
    }

    public static function getLatestQuestion($limit)
    {
        if (Cache::has('latest_question')) {
            return Cache::get('latest_question');
        }
        $questions = Question::where('blocked', '=', '0')
            ->where('blocked', '=', '0')
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get(['id', 'title', 'slug', 'number_clip', 'number_answer', 'solved', 'published_at']);
        Cache::put('latest_question', $questions, 20);
        return $questions;
    }
    /**
     * get author infomation for view
     * @return array infomation
     */
    public function getAuthor()
    {
        $questionViewData = [];
        $questionViewData['id'] = $this->user->id;
        $questionViewData['name'] = $this->user->name;
        $questionViewData['username'] = $this->user->username;
        $questionViewData['url'] = route('getUser', $this->user->username);
        $questionViewData['publishedQuestion'] = $this->user->publishedQuestion()->count();
        $questionViewData['followerUser'] = $this->user->followers()->count();
        $questionViewData['avatar'] = user_img_url($this->user, 1000);
        $questionViewData['getUserFollowers'] = route('getUserFollowers', $this->user->username);

        return $questionViewData;
    }
    /**
     * get people same group with current user login
     * @param  integer $skip  position get record for paginate
     * @param  int  $limit number of record will return
     * @return array infomation
     */
    private function getUserGroup($skip = 0, $limit = Question::LIMIT_USER_REQUEST)
    {
        $groups = DB::table('group_users')->join('groups', 'groups.id', ' = ', 'group_users.group_id')->where('group_users.user_id', Auth::id())->select(['groups.id'])->get();
        $group_ids = [];
        foreach ($groups as $key => $group) {
            $group_ids[] = $group->id;
        }
        $users = User::join('group_users', 'users.id', '=', 'group_users.user_id')->whereIn('group_users.group_id', $group_ids)->skip($skip)->take($limit)->get(['users.id', 'users.name', 'users.username', 'users.total_best_answer', 'users.total_helpful_answer']);

        return $this->getRequestInfo($users);
    }
    /**
     * check status of question and return text and class
     * @return array text and class info
     */
    private function getStatusSolved()
    {
        $solved = [];
        if ($this->solved) {
            $solved['style'] = '';
            $solved['text'] = trans('labels.question.solved');
            return $solved;
        }
        $solved['style'] = ' style="background-color: #d8d8d8"';
        $solved['text'] = trans('labels.question.unsolved');
        return $solved;
    }
}
