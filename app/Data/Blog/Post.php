<?php namespace App\Data\Blog;

use App\Events\PostMentionNotificationHandler;
use App\Events\PostStockNotificationHandler;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Carbon\Carbon;
use App\Data\System\User;
use App\Services\PostService;
use GrahamCampbell\Markdown\Facades\Markdown;
use DB;
use App\Services\CommentService;
use App\Facades\Authority;
use Event;

class Post extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    CONST TOP_STOCKED_LIMIT = 10;
    CONST TOP_RANDOM_STOCKED = 10;
    CONST USER_POPULAR_POST = 7;
    CONST RELATED_POST_LIMIT = 5;
    CONST POPULAR_POST_RAMDOM = 20;
    CONST POPULAR_POST_LIMIT_TIME = 30;
    CONST POPULAR_POST_LIMIT_IN_POST_DETAIL = 7;
    CONST POPULAR_POST_LIMIT_IN_POST_INDEX = 10;
    CONST POPULAR_POST_MAGAZINE_LIMIT_INDEX = 4;
    CONST MAGAZINE_LIMIT_BATCH = 100;
    CONST LIMIT_USER_STOCK_DISPLAY = 4;
    CONST USER_STOCK_PERPAGE = 7;
    CONST AGE_GRAVITY_FOR_RANKING = 1.98;
    CONST TOP_ALL_FILTER = 15;
    CONST MIN_HOT_POSTS_TO_DISPLAY = 4;
    CONST IS_WIKI = 1;
    CONST NOT_WIKI = 0;
    CONST STATUS_PUBLISHED = 0;
    CONST STATUS_DRAFT = 1;
    CONST FIND_BY_POST = 0;
    CONST FIND_BY_CATEGORY = 1;

    protected $table = 'posts';
    protected $guarded = ['id'];

    public static $createRules = [
        'title' => 'required',
        'content' => 'required',
        'category' => 'required',
        'language_code' => 'required',
    ];

    /**
     * Define categories relation. A Post belongs to many Categories.
     * @return mixed
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_categories')->withTimestamps();
    }

    public function postCategories()
    {
        return $this->hasMany('App\Data\Blog\PostCategory');
    }

    /**
     * Define categories relation. A Post belongs to an user.
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class)->with('avatar', 'profile');

    }

    /**
     * Return Post's content that parsed down.
     * @return string
     */
    public function getParsedContent()
    {
        $content = $this->content;
        if ($this->isWiki()) {
            $content = PostService::getParsedWiki($this);
        }
        $escaped = markdown_escape($content);
        $render = new \Parsedown();
        $render = $render->text($escaped);
        return cleanHtml($render);
    }

    public function isWiki()
    {
        return ($this->wiki_flag == self::IS_WIKI);
    }

    public function wiki()
    {
        return $this->hasOne('App\Data\Blog\Wiki');
    }

    public function postSeries()
    {
        return $this->hasOne('App\Data\Blog\PostSeries');
    }

    /**
     * Check whether the post is published or not
     * @return bool
     */
    public function isPublished()
    {
        return $this->published_at !== null;
    }

    /**
     * Increase posts_count of category
     */
    public function increaseCategoryPostsCount()
    {
        if ($this->isPublished()) {
            foreach ($this->categories as $category) {
                $category->increment('posts_count');
            }
        }
    }

    /*
    * Decrease posts_count of category
    *
    */
    public function decreaseCategoryPostsCount()
    {
        if (!$this->isPublished()) {
            foreach ($this->categories as $category) {
                $category->decrement('posts_count');
            }
        }
    }

    /**
     * Define drafted scope
     * @param $query
     * @return mixed
     */
    public function scopeDrafted($query)
    {
        return $query->whereNull('published_at');
    }

    /**
     * Define published scope
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Get all categories name of the post
     * @return array
     */
    public function getCategoriesName()
    {
        $categories = $this->categories()->get()->toArray();
        $categories = array_pluck($categories, 'name');
        return $categories;
    }

    public function userStocks()
    {
        return $this->belongsToMany('App\Data\System\User', 'stocks')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany('App\Data\Blog\Report');
    }

    public function postHelpfuls()
    {
        return $this->hasMany('App\Data\Blog\PostHelpful');
    }

    public function userReports()
    {
        return $this->belongstoMany('App\Data\System\User', 'reports');
    }

    /**
     * Define relationship with Comment
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Data\Blog\Comment');
    }

    /**
     * Define relationship with Stock
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany('App\Data\Blog\Stock');
    }

    /**
     * Define relationship with Notification
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('App\Data\Blog\Notification');
    }

    /**
     * Define relationship with StockRankingWeekly
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockRankingWeekly()
    {
        return $this->hasMany('App\Data\Blog\StockRankingWeekly');
    }

    public function postViews()
    {
        return $this->hasOne('App\Data\Blog\PostView');
    }

    public static function boot()
    {
        parent::boot();

        // Before delete
        static::deleting(function ($post) {
            $post->stocks()->delete();
            if (!is_null($post->published_at)) {
                DB::table('post_categories')
                    ->where('post_id', $post->id)
                    ->join('categories', 'post_categories.category_id', '=', 'categories.id')
                    ->decrement('categories.posts_count');
            }
            $post->postCategories()->delete();
            $post->comments()->delete();
            $post->stockRankingWeekly()->delete();
            $post->postSeries()->delete();
        });

        // After created
        static::created(function ($post) {
            $post->encryptedId = encrypt_id($post->id);
            $post->save();

            PostSeries::create([
                'post_id' => $post->id,
                'group_series_id' => 0,
                'language_code' => $post->language_code,
                'published_at' => $post->published_at,
            ]);
        });

        // After update
        static::updated(function ($post) {
            if (!$post->encryptedId) {
                $post->encryptedId = encrypt_id($post->id);
                $post->save();
            }
            if ($post->isPublished()) {
                Event::fire(PostMentionNotificationHandler::EVENT_NAME, $post);
            }

            $post->postSeries()->update([
                'language_code' => $post->language_code,
                'published_at' => $post->published_at,
            ]);
        });
    }

    public function isStockedBy($user)
    {
        if ($user) {
            return (Stock::where('post_id', $this->id)->where('user_id', $user->id)->count() > 0);
        }
        return false;
    }

    public function addStock($user)
    {
        if (!$this->isStockedBy($user)) {
            $stock = $this->stocks()->create(['user_id' => $user->id]);
            $this->increment('stocks_count');
            Event::fire(PostStockNotificationHandler::EVENT_NAME, $stock);
            return true;
        }
        return false;
    }

    public function removeStock($user)
    {
        if ($this->isStockedBy($user)) {
            $this->decrement('stocks_count');
            $this->userStocks()->detach($user->id);
            return true;
        }
        return false;
    }

    public static function feed($userId)
    {
        $followedUserIds = UserRelationships::where('follower_id', $userId)->lists('followed_id');
        $followedCategoryIds = FollowCategory::where('user_id', $userId)->lists('category_id');
        $postCategoryIds = PostCategory::whereIn('category_id', $followedCategoryIds)->lists('post_id');

        $posts = Post::with('categories', 'user', 'wiki')
            ->where(function ($query) use ($postCategoryIds, $followedUserIds) {
                return $query->where(function ($firstQuery) use ($postCategoryIds) {
                    return $firstQuery->whereIn('id', $postCategoryIds);
                })->orWhere(function ($secondQuery) use ($followedUserIds) {
                    return $secondQuery->orWhereIn('user_id', $followedUserIds);
                });
            })->orderBy('published_at', 'desc');

        return $posts;
    }

    public function related()
    {
        $postsRelated = [];
        $categories = $this->postCategories()->lists('category_id');
        if (count($categories) > 0) {
            $postIds = PostCategory::whereIn('category_id', $categories)->limit(30)->lists('post_id');
            $language = UserPostLanguage::getCurrentUserLanguages();
            $postsRelated = Post::whereNotNull('published_at')
                ->whereIn('id', $postIds)
                ->where('id', '!=', $this->id)
                ->whereNotNull('encrypted_id');
            if ($language[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                $postsRelated = $postsRelated->whereIn('language_code', $language);
            }
            $postsRelated = $postsRelated->orderBy('views_count', 'desc')
                ->orderBy('published_at', 'desc')
                ->take(self::RELATED_POST_LIMIT)
                ->get();
        }
        return $postsRelated;
    }

    public function popular()
    {
        return PostService::getPopularPosts(self::POPULAR_POST_LIMIT_IN_POST_DETAIL);
    }

    public static function getStockRankingByPost($limit = null)
    {
        $lastWeek = Carbon::now()->subWeek()->weekOfYear;
        $thisYear = Carbon::now()->year;
        $stockedPostRanking = DB::table('posts')
            ->select(DB::raw('posts.id, posts.language_code, posts.user_id, 
                posts.encrypted_id, posts.thumbnail, posts.title, 
                stock_ranking_weekly.stocks_count as stocksCount, users.username'))
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->join('stock_ranking_weekly', 'stock_ranking_weekly.post_id', '=', 'posts.id')
            ->where('stock_ranking_weekly.week', $lastWeek)
            ->where('stock_ranking_weekly.year', $thisYear)
            ->whereNotNull('posts.published_at')
            ->whereNotNull('posts.encrypted_id')
            ->orderBy('stocksCount', 'desc');
        $userLanguages = UserPostLanguage::getCurrentUserLanguages();
        if (isset($userLanguages[0]) && $userLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $stockedPostRanking = $stockedPostRanking->whereIn('posts.language_code', $userLanguages);
        }
        if ($limit) {
            $stockedPostRanking->take($limit);
        }
        return $stockedPostRanking->get();
    }

    public static function getStockedRankingByCategoryId($categoryId)
    {
        $lastWeek = Carbon::now()->subWeek()->weekOfYear;
        $thisYear = Carbon::now()->year;

        $stockedPostRanking = DB::table('posts')
            ->select(DB::raw('posts.id,posts.encrypted_id, posts.user_id,
                posts.title, stock_ranking_weekly.stocks_count as stocksCount, users.username'))
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->join('stock_ranking_weekly', 'stock_ranking_weekly.post_id', '=', 'posts.id')
            ->join('post_categories', 'stock_ranking_weekly.post_id', '=', 'post_categories.post_id')
            ->where('post_categories.category_id', $categoryId)
            ->where('stock_ranking_weekly.week', $lastWeek)
            ->where('stock_ranking_weekly.year', $thisYear)
            ->whereNotNull('posts.published_at')
            ->whereNotNull('posts.encrypted_id')
            ->orderBy('stocksCount', 'desc')
            ->get();
        return $stockedPostRanking;
    }

    /**
     * @param string $encryptedId
     * @return Post mixed
     */
    public static function findByEncryptedId($encryptedId)
    {
        if (empty($encryptedId)) {
            return null;
        }
        $post = Post::where('encrypted_id', $encryptedId)->first();
        if (!empty($post)) {
            return $post;
        }
        return null;
    }

    public function getLatestUsersStock($quantity = self::LIMIT_USER_STOCK_DISPLAY)
    {
        return $this->userStocks()
            ->latestStock()
            ->take($quantity)
            ->get();
    }

    public function getMoreUserStock($start = self::USER_STOCK_PERPAGE)
    {
        return $this->userStocks()
            ->skip($start)
            ->latestStock()
            ->take(self::USER_STOCK_PERPAGE)
            ->get();
    }

    public function getComments($limit = null, $offset = null)
    {
        if (is_null($limit) && is_null($offset)) {
            return false;
        } else {
            $limit = is_null($limit) ? CommentService::COMMENT_DISPLAY_PER_PAGE : $limit;
            $offset = is_null($offset) ? 0 : ($offset * CommentService::COMMENT_DISPLAY_PER_PAGE);
            $comments = $this->comments()->with('user')->take($limit)->offset($offset)->orderBy('created_at', 'asc')->get();
        }
        return $comments;
    }

    public function canBeDeletedAndEditedBy($user)
    {
        if ($user->id == $this->user_id || Authority::hasRoleByUser($user, 'admin')) {
            return true;
        } else {
            return false;
        }
    }

    public function isVotedBy($user)
    {
        if (Auth::check()) {
            return (PostHelpful::where('post_id', $this->id)->where('user_id', $user->id)->count() > 0);
        }
        return false;
    }

    public function isVotedHelpfulBy($user)
    {
        if (Auth::check()) {
            return (PostHelpful::where('post_id', $this->id)->where('user_id', $user->id)->where('helpful', PostHelpful::HELPFUL_YES)->count() > 0);
        }
        return false;
    }

    public function warningNotHelpful()
    {
        $helpful = PostHelpful::select(
            DB::raw('COUNT(IF(helpful, 1, NULL)) AS helpful_yes'),
            DB::raw('COUNT(IF(helpful, NULL, 1)) AS helpful_no'))
            ->where('post_id', $this->id)
            ->get();
        return (($helpful[0]['helpful_no'] - $helpful[0]['helpful_yes']) >= PostHelpful::LIMIT_NOT_HELPFUL_WARNING);
    }

    public function getCountHelpful()
    {
        $helpful = PostHelpful::select(
            DB::raw('COUNT(IF(helpful, 1, NULL)) AS helpful_yes'),
            DB::raw('COUNT(IF(helpful, NULL, 1)) AS helpful_no'))
            ->where('post_id', $this->id)
            ->get();
        return $helpful;
    }

    public function theme()
    {
        return $this->belongsTo('App\Data\Blog\MonthlyTheme', 'monthly_theme_id');
    }

    public function canEditedBy($user)
    {
        $groupPost = GroupPost::where('post_id', $this->id)->first();
        if ($groupPost) {
            $group = Group::where('id', $groupPost->group_id)->first();
            $groupSettingEditPost = isset($group->groupSetting->edit_post_flag) ? $group->groupSetting->edit_post_flag : false;

            switch ($groupSettingEditPost) {
                case GroupSetting::ALL_CAN_EDIT_POST:
                    return true;
                case GroupSetting::ONLY_ADMIN_CAN_EDIT_POST:
                    if ($user->isAdminOf($group) || ($this->user_id == $user->id)) {
                        return true;
                    }
                    break;
                case GroupSetting::ONLY_AUTHOR_CAN_EDIT_POST:
                    if ($this->user_id == $user->id) {
                        return true;
                    }
                    break;
                default:
                    return false;
            }
        }
        return false;
    }

    public function scopeValidForContest($query, $contest)
    {
        return $query->select(DB::raw(
            'id,
            user_id,
            encrypted_id,
            title,
            count(title) as total_articles,
            sum(stocks_count) as total_stocks,
            sum(comments_count) as total_comments'
        ))
            ->whereBetween('published_at', [$contest->term_start, $contest->term_end])
            ->where(self::getTableName() . '.monthly_theme_subject_id', $contest->monthly_theme_subject_id)
            ->orderByRaw(DB::raw('GREATEST(COALESCE(sum(stocks_count), 0), COALESCE(sum(comments_count), 0)) DESC'));
    }


    public function getGroupSeriDetail()
    {
        $groupSeries = GroupSeriesItem::where('post_id', $this->id)->lists('group_series_id');
        $groupSeriesDetail = [];
        if (!$groupSeries->isEmpty()) {
            $groupSeries = GroupSeries::whereIn('id', $groupSeries)->get();
            $groupSeriesDetail = [];
            foreach ($groupSeries as $index => $aGroupSeries) {
                $groupSeriesDetail[$index]['aGroupSeries'] = $aGroupSeries;
                $groupSeriesItems = $aGroupSeries->groupSeriesItems()->where('type', GroupSeries::URL_TYPE_POST);
                $groupSeriesDetail[$index]['totalPostSeries'] = $groupSeriesItems->count();
                $postsIdInSeries = $groupSeriesItems->limit(GroupSeriesItem::LIMIT_ITEM_ON_POST_DETAIL)->lists('post_id');
                $groupSeriesDetail[$index]['groupSeriesItems'] = Post::with('user')
                    ->whereIn('id', $postsIdInSeries)
                    ->orderBy('published_at', 'DESC')
                    ->get();
            }
        }
        return $groupSeriesDetail;
    }

    public function getImageSeo()
    {
        $image_seo = [];
        if (!empty($this->thumbnail)) {
            try {
                list($width, $height) = @getimagesize(public_path() . $this->thumbnail);
                $image_seo['siteImage'] = $this->thumbnail;
                $image_seo['imageWidth'] = $width;
                $image_seo['imageHeight'] = $height;
            } catch (\Exception $e) {
                return false;
            }
        }
        return $image_seo;
    }

    public static function deletePostAndGroupPost($post, $currentUser)
    {
        if (!$post || !$post->canBeDeletedAndEditedBy($currentUser)) {
            return ['message' => trans('messages.permission_denied'), 'status_code' => 400];
        }
        // Start transaction!
        DB::beginTransaction();
        try {
            $postGroup = GroupPost::where('post_id', $post->id)->first();
            $post->delete();
            if (!is_null($postGroup)) {
                $postGroup->delete();
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return ['message' => trans('messages.action_failed'), 'status_code' => 400];
        }
        DB::commit();
        $text = $post->isPublished ? trans('labels.post') : trans('labels.draft');
        return ['message' => trans('messages.post.deleted_success', ['text' => $text]), 'status_code' => 200];
    }
}
