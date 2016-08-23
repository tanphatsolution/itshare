<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Events\LogViewPostDetailHandler;

use Event;
use Response;
use View;
use URL;
use Config;
use Validator;

use App\Data\Blog\Activity;
use App\Data\Blog\Category;
use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupSetting;
use App\Data\Blog\Notification;
use App\Data\Blog\Post;
use App\Data\Blog\Setting;
use App\Events\ViewPostHandler;
use App\Data\Blog\Wiki;

use App\Services\CategoryService;
use App\Services\GroupService;
use App\Services\HelperService;
use App\Services\PostService;
use App\Services\UserService;

class PostsController extends BaseController
{

    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth', [
            'except' => [
                'show', 'getIndex', 'filter', 'counter',
                'getRelated', 'getListUserStockModal','getListUserStock',  'filtersInAll'
            ]
        ]);
        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => [
                'getList', 'getStatistic','changeLanguage',
            ]
        ]);
        $this->viewData['title'] = trans('titles.posts');
    }

    public static function counter(Request $request)
    {

        $post = Post::find((int)$request->get('post_id'));
        if ($post) {
            Event::fire(ViewPostHandler::EVENT_NAME, $post);
            return Response::json([$post->viewsCount]);
        }

        return Response::json([0]);
    }

    /**
     * Default post page
     * Route /posts/index
     * @return response
     */
    public function getIndex(Request $request, $wall = PostService::WALL_ALL, $filter = PostService::SELECT_FILTER)
    {
        $this->viewData['filterBy'] = $filter;
        if ($request->has('wall')) {
            $wall = $request->get('wall');
        }

        $seoLang = $request->get('lang', '');

        if ($request->ajax()) {
            $wall = $request->get('wall');
            $pageCount = $request->get('pageCount');
            $filterBy = $request->get('filterBy');
            $posts = PostService::getPostFollowInWall($wall, $pageCount, $filterBy, $seoLang);

            if ($wall == PostService::WALL_ALL) {
                $postCount = $posts['posts']->count();
                $html = View::make('post._list_posts_series', ['posts' => $posts['posts'],
                    'currentUser' => $this->currentUser, 'lang' => $this->lang])->render();
            } else {
                $postCount = $posts->count();
                $html = View::make('post._list_posts_title_tags', ['posts' => $posts,
                    'currentUser' => $this->currentUser, 'lang' => $this->lang])->render();
            }

            $seeMore = trans('labels.load_more');
            $hideSeeMore = false;
            if ($postCount < PostService::PER_PAGE) {
                $hideSeeMore = true;
            }
            return Response::json(['html' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            $posts = PostService::getPostFollowInWall($wall, 0, $filter, $seoLang);

            $titleList = [
                PostService::WALL_ALL => trans('titles.top_page', ['title' => trans('titles.top_page_title_after')]),
                PostService::WALL_FEED => trans('titles.follow_posts'),
                PostService::WALL_STOCK => trans('titles.my_clip'),
                PostService::WALL_RECENT => trans('titles.recent_clipped_posts'),
                PostService::WALL_TOP => trans('titles.top_post'),
                PostService::WALL_HELPFUL => trans('titles.helpful_post'),
            ];

            $this->viewData['title'] = array_key_exists($wall, $titleList)
                ? $titleList[$wall]
                : trans('titles.top_page', ['title' => trans('titles.top_page_title_after')]);
            $this->viewData = array_merge($this->viewData, $posts);
            $this->viewData['seoLang'] = $seoLang;

            $this->viewData['topCategories'] = $this->categories;
            $this->viewData['populars'] = PostService::getPopularPosts(Post::POPULAR_POST_LIMIT_IN_POST_INDEX);
            return view('post.index', $this->viewData);
        }
    }

    public function filter(Request $request)
    {
        $input = $request->all();
        $wall = $input['wall'];
        $filterBy = $input['filter_by'];
        $posts = PostService::filterPosts($wall, $filterBy);
        return View::make('post._list_posts_title_tags')->with('posts', $posts)->with('currentUser', $this->currentUser);
    }

    public function create($themeId = null)
    {
        if (!PostService::checkDraftAvailable()) {
            return redirect()->route('getDrafts', ['encryptedId' => ''])
                ->withErrors([trans('messages.post.max_drafts_error')]);
        }
        $this->viewData['title'] = trans('titles.create_post');
        $this->viewData['fullSize'] = true;
        $this->viewData['categories'] = CategoryService::getAllCategoriesName();
        $topCategories = Category::getTopRecentCategories($this->currentUser);
        $this->viewData['topCategories'] = $topCategories;
        $this->viewData['editorThemeList'] = Setting::getThemeSettingFields();
        $this->viewData['themes'] = PostService::getThemesOption($themeId);
        $this->viewData['fixedHeaderOnScroll'] = false;
        $this->viewData['languageCode'] = $this->currentUser->setting()->first()->default_post_language;
        $this->viewData['groups'] = GroupService::getGroupsCanPostOf($this->currentUser);

        return View::make('post.create', $this->viewData);
    }

    public function postCreate(Request $request)
    {
        $inputs = $request->all();
        if (!PostService::checkDraftAvailable()) {
            return redirect()->route('getDrafts', ['encryptedId' => ''])
                ->withErrors([trans('messages.post.max_drafts_error')]);
        }
        $this->viewData['title'] = trans('titles.create_post');
        $this->viewData['fullSize'] = true;
        $this->viewData['categories'] = CategoryService::getAllCategoriesName();
        $topCategories = Category::getTopRecentCategories($this->currentUser);
        $this->viewData['topCategories'] = $topCategories;
        $this->viewData['editorThemeList'] = Setting::getThemeSettingFields();
        $this->viewData['themes'] = PostService::getThemesOption(null);
        $this->viewData['fixedHeaderOnScroll'] = false;
        $this->viewData['languageCode'] = $this->currentUser->setting()->first()->default_post_language;
        $this->viewData['groups'] = GroupService::getGroupsCanPostOf($this->currentUser);
        if (isset($inputs['groupId'])) {
            $this->viewData['groupId'] = $inputs['groupId'];
        }
        if (isset($inputs['privacyType'])) {
            $this->viewData['privacyType'] = $inputs['privacyType'];
        }

        return View::make('post.create', $this->viewData);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = $this->currentUser->id;
        $validator = Validator::make($input, Post::$createRules);
        if ($validator->fails()) {
            $input['content_show'] = $input['content'];
            return redirect()->route('getPostCreateTheme', $input['monthly_theme_id'])
                ->withInput($input)
                ->withErrors($validator);
        }
        $post = PostService::create($input);
        PostService::attachCategories($post, $input['category']);
        return redirect(htmlentities(url_to_post($post)));
    }

    public function show(Request $request, $username, $encryptedId)
    {
        $post = Post::findByEncryptedId($encryptedId);
        if (!$post || (!$post->isPublished() && !$post->share_by_url && (is_null($this->currentUser) || !$post->canBeDeletedAndEditedBy($this->currentUser)))) {
            return response()->view('errors.404', $this->viewData, 404);
        }
        $postAuthor = $post->user()->firstOrFail();
        if ($postAuthor->username != $username) {
            return redirect()->route('post.detail', ['username' => $postAuthor->username, 'encryptedId' => $post->encryptedId]);
        }
        $groupPostData = GroupPost::getGroupPostData($post->id, $this->currentUser, $post);
        $this->viewData = array_merge($this->viewData, $groupPostData);
        if ($request->has('ref')) {
            $this->viewData['ref'] = $request->get('ref');
        }
        $groupSeriesDetail = $post->getGroupSeriDetail();
        $this->viewData['groupSeriesDetail'] = $groupSeriesDetail;
        if ($post->isWiki()) {
            $this->viewData['wiki'] = Wiki::where('post_id', $post->id)->first();
        }
        $theme = $post->theme()->first();
        if ($theme) {
            $this->viewData['monthlyThemeSubject'] = $theme->themeSubject()->first();
            $this->viewData['monthlyTheme'] = $theme;
        }
        $post->setAttribute('numberStock', $post->stocks_count);
        $numberStock = $post->getAttribute('numberStock');
        Notification::checkReferenceLink($request->all());
        $categories = $post->categories;
        $posts = $postAuthor->posts()->get();
        $userStock = $post->userStocks()->first();
        $content = $post->getParsedContent();
        $this->viewData['title'] = trans('titles.group_post', ['title' => htmlspecialchars($post->title)]);
        $this->viewData['description'] = HelperService::getPostDescription($content, 400);
        $this->viewData['url'] = URL::current();
        $this->viewData['keywords'] = implode(', ', $categories->lists('name')->toArray());
        $imageSocialSeo = $post->getImageSeo();
        $this->viewData = array_merge($this->viewData, $imageSocialSeo);
        $this->viewData['post'] = $post;
        $this->viewData['categories'] = $categories;
        $this->viewData['posts'] = $posts;
        $this->viewData['postContent'] = PostService::lazyLoadImg($content);
        $this->viewData['userStocks'] = $userStock;
        $this->viewData['numberStock'] = $numberStock;
        $this->viewData['screen'] = Activity::SCREEN_POST_DETAIL;
        Event::fire(LogViewPostDetailHandler::EVENT_NAME, $post);
        return View::make('post.show', $this->viewData)->with('user', $postAuthor);
    }

    public function edit($encryptedId)
    {
        if (!$encryptedId) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        $post = Post::findByEncryptedId($encryptedId);
        if (!$post) {
            return redirect()->route('getPostIndex')
                ->with('message_error', trans('messages.not_exist', ['item' => trans('labels.post')]));
        }

        if (!$post->canBeDeletedAndEditedBy($this->currentUser)) {
            if (!$post->canEditedBy($this->currentUser)) {
                return redirect()->route('getPostIndex')
                    ->with('message_error', trans('messages.permission_denied'));
            }
        }
        $this->viewData['themes'] = PostService::getThemesOption($post->monthly_theme_id);
        $topCategories = Category::getTopRecentCategories($this->currentUser);
        $this->viewData['post'] = $post;
        $this->viewData['title'] = trans('titles.edit_post');
        $this->viewData['categories'] = CategoryService::getAllCategoriesName();
        $this->viewData['fullSize'] = true;
        $this->viewData['topCategories'] = $topCategories;
        $this->viewData['editorThemeList'] = Setting::getThemeSettingFields();
        $this->viewData['fixedHeaderOnScroll'] = false;

        $this->viewData['groups'] = GroupService::getGroupsCanPostOf($this->currentUser);
        $groupPost = GroupPost::where('post_id', $post->id)->first();
        $this->viewData['groupPost'] = $groupPost;
        $groupPrivacyProtected = null;
        if (!is_null($groupPost)) {
            $groupPrivacyProtected = GroupSetting::where('group_id', $groupPost->group_id)
                ->where('privacy_flag', GroupSetting::PRIVACY_PROTECTED)
                ->first();
        }
        $this->viewData['groupPrivacyProtected'] = $groupPrivacyProtected;
        $this->viewData['isAuthor'] = ($this->currentUser->id == $post->user_id);

        return View::make('post.edit', $this->viewData);
    }

    public function update(Request $request, $encryptedId)
    {

        if (!$encryptedId) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        $input = $request->all();
        $post = Post::findByEncryptedId($encryptedId);
        if (!$post) {
            return redirect()->route('getPostIndex')
                ->with('message_error', trans('messages.not_exist', ['item' => trans('labels.post')]));
        }
        if (!$post->canBeDeletedAndEditedBy($this->currentUser)) {
            if (!$post->canEditedBy($this->currentUser)) {
                return redirect()->route('getPostIndex')
                    ->with('message_error', trans('messages.permission_denied'));
            }
        }
        $validator = Validator::make($input, Post::$createRules);
        if ($validator->fails()) {
            return redirect()->route('post.edit', $encryptedId)
                ->withInput($input)
                ->withErrors($validator);
        }
        if (PostService::update($post, $input)) {
            return redirect(htmlentities(url_to_post($post)));
        }
        return View::make('home.index');
    }

    public function destroy(Request $request, $encryptedId)
    {
        if (!$request->ajax()) {
            return Response::json(['message' => trans('messages.invalid_request')], 400);
        }
        $post = Post::findByEncryptedId($encryptedId);
        $messages = Post::deletePostAndGroupPost($post, $this->currentUser);
        return Response::json(['message' => $messages['message']], $messages['status_code']);
    }

    public function preview(Request $request)
    {
        $input = $request->all();
        $preview = markdown_escape($input['content']);
        $markdown = new Markdown(new ParsedownExtra());
        return PostService::newMarkdownForImage($markdown->render($preview));
    }

    public function draft($encryptedId = '')
    {
        if ($encryptedId) {
            $post = Post::findByEncryptedId($encryptedId);
            if ($post && $this->currentUser->id !== $post->userId) {
                $post = null;
            }
        } else {
            $post = null;
        }

        $drafts = $this->currentUser->posts()->drafted()->orderBy('updated_at', 'DESC')->get();
        $this->viewData['post'] = $post;
        $this->viewData['drafts'] = $drafts;
        $this->viewData['maxDrafts'] = Config::get('limitation.max_drafts');
        $this->viewData['title'] = trans('titles.draft');
        return View::make('post.draft', $this->viewData);
    }

    public function getListUserStock(Request $request)
    {
        if (!$request->ajax()) {
            return Redirect::action('HomeController@getTopPage');
        }

        $message = trans('labels.load_more');
        $start = $request->get('start');
        $postId = (int)$request->get('postId');
        $post = Post::find($postId);
        $totalUserStocks = $post->userStocks()->count();
        $this->viewData['userStocks'] = $post->getMoreUserStock($start);
        $html = View::make('user._list_users_stock', $this->viewData)->render();

        if ($start >= $totalUserStocks - Post::USER_STOCK_PERPAGE) {
            $end = true;
        }

        return Response::json([
            'html' => $html,
            'end' => isset($end) ? $end : false,
            'message' => isset($end) ? null : $message,
        ]);
    }

    public function autoSaveDraft(Request $request)
    {
        if ($request->ajax()) {
            $message = null;
            $input = $request->all();
            $input['user_id'] = $this->currentUser->id;
            $input['title'] = !empty($input['title']) ? $input['title'] : 'Untitle';
            $input['category'] = !empty($input['category']) ? $input['category'] : 'Uncategory';
            $input['post_privacy_flag'] = empty($input['post_privacy_flag']) ? GroupPost::GROUP_POST_PUBLIC : $input['post_privacy_flag'];
            $validator = Validator::make($input, Post::$createRules);
            if (!$validator->fails()) {
                if (empty($input['encrypted_id'])) {
                    $post = PostService::create($input);

                    if ($input['category'] != 'Uncategory') {
                        PostService::attachCategories($post, $input['category']);
                    }
                    $encryptedId = $post->encrypted_id;
                    $saved_time = date('H:i:s', strtotime($post->updated_at));

                    return Response::json([
                        'message' => $message,
                        'encrypted_id' => $encryptedId,
                        'saved_time' => $saved_time,
                        'saved' => true,
                    ], 200);
                } else {
                    $encryptedId = $input['encrypted_id'];
                    $post = Post::findByEncryptedId($encryptedId);
                    if ($post && !$post->isPublished()) {
                        PostService::update($post, $input);
                        $saved_time = date('dS F Y H:i:s', strtotime($post->updated_at));

                        return Response::json([
                            'message' => $message,
                            'encrypted_id' => $encryptedId,
                            'saved_time' => $saved_time,
                            'saved' => true,
                        ], 200);
                    }
                }
            } else {
                return Response::json(['message' => $message], 400);
            }
        }
    }

    public function save(Request $request)
    {
        $getInput = $request->all();
        if (!empty($getInput['encrypted_id'])) {
            return $this->update($request, $getInput['encrypted_id']);
        } else {
            return $this->store($request);
        }
    }

    public function unpublished(Request $request, $encryptedId)
    {
        $unpublishedPost = null;
        $message = null;
        if (!$request->ajax()) {
            $message = trans('messages.invalid_request');
            return Response::json(['message' => $message], 400);
        }
        if (!PostService::checkDraftAvailable()) {
            $message = trans('messages.post.max_drafts_error');
            return Response::json(['message' => $message], 400);
        }
        $post = Post::findByEncryptedId($encryptedId);
        if (!$message && (!$post || !$post->canBeDeletedAndEditedBy($this->currentUser))) {
            $message = trans('messages.permission_denied');
        } else {
            $post->published_at = null;
            $unpublishedPost = $post->save();
            $post->decreaseCategoryPostsCount();
        }
        if (($message === null) && ($unpublishedPost === null)) {
            $message = trans('messages.action_failed');
        }
        if ($message !== null) {
            return Response::json(['message' => $message], 400);
        } else {
            $message = trans('messages.post.unpublished_success');
            return Response::json(['message' => $message], 200);
        }
    }

    public function getList(Request $request)
    {
        $options = PostService::getOptionsSearch();
        $posts = Post::with('categories', 'user')->whereNotNull('encrypted_id')->orderBy('published_at', 'desc')->paginate(PostService::ADMIN_PER_PAGE);
        $input = $request->all();
        if (isset($input['filter'])) {
            $this->viewData['input'] = $input;
            $posts = PostService::searchBy($input);
        }
        $this->viewData['title'] = trans('messages.post.manage_title');
        $this->viewData['posts'] = $posts;
        $this->viewData['options'] = $options;
        $this->viewData['filters'] = PostService::getListFilter();

        return View::make('post.list', $this->viewData);
    }

    public function filtersInAll(Request $request, $filter = null, $pageCount = 0, $filterBy = PostService::SELECT_FILTER)
    {
        if ($request->ajax()) {
            $posts = PostService::getFiltersInAllPage($filter, $filterBy);
            $offset = $pageCount * PostService::PER_ALL_PAGE;
            $posts = $posts->take(PostService::PER_ALL_PAGE)->skip($offset)->get();
            $hideSeeMore = false;
            $seeMoreLabel = trans('labels.load_more');
            if ($posts->count() < PostService::PER_ALL_PAGE) {
                $hideSeeMore = true;
            }
            $result = View::make('post._list_posts_title_tags', ['posts' => $posts, 'currentUser' => $this->currentUser])->render();
            return Response::json([
                'data' => $result,
                'hideSeeMore' => $hideSeeMore,
                'seeMoreLabel' => $seeMoreLabel,
            ], 200);
        }
    }

    public function getStatistic(Request $request)
    {
        $weeks = empty($request->get('weeks')) ? PostService::FIRST_HALF : $request->get('weeks');
        $month = empty($request->get('month')) ? date('m') : $request->get('month');
        $year = empty($request->get('year')) ? date('Y') : $request->get('year');
        $filter = empty($request->get('filter')) ? UserService::FILTER_ALL : $request->get('filter');
        $optionsTime = [
            'weeks' => $weeks,
            'month' => $month,
            'year' => $year,
            'daysInMonth' => cal_days_in_month(CAL_GREGORIAN, $month, $year),
        ];
        $this->viewData['statisticByWeek'] = PostService::statisticByWeek($weeks, $year, $filter);
        $this->viewData['statisticInMonth'] = PostService::statisticInMonth($month, $year, $filter);
        $this->viewData['statisticAll'] = PostService::statisticAll($filter);
        $this->viewData['optionsTime'] = $optionsTime;
        $this->viewData['filter'] = $filter;
        $this->viewData['filterOptions'] = PostService::getFilterOptions();
        $this->viewData['weekOption'] = PostService::getWeeksOption();
        return View::make('post.statistic', $this->viewData);
    }

    public function getListUserStockModal(Request $request)
    {
        if ($request->ajax()) {
            $post = Post::find((int)$request->get('postId'));
            if ($post) {
                $modal = View::make('modals.user_stock', [
                    'userStocks' => $post->getLatestUsersStock(Post::USER_STOCK_PERPAGE),
                    'postId' => $post->id,
                    'currentUser' => $this->currentUser
                ])->render();
                return Response::json(['result' => true, 'modal' => $modal], 200);
            }
            return Response::json(['result' => false], 200);
        }
    }

    public function changeLanguage(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $post = Post::find((int)$input['postId']);
            if ($post) {
                $postUpdate = $post->update(['language_code' => $input['language']]);
                return Response::json(['success' => $postUpdate], 200);
            }
        }
        return Response::json(['success' => false], 200);
    }


    public function getRelated() {
        $encrypted_id = \Input::get('encrypted_id');
        $post = Post::findByEncryptedId($encrypted_id);
        $this->viewData['postRelated'] = $post->related();
        return View::make('post.Related', $this->viewData);
    }
}
