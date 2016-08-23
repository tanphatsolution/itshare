<?php namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Jenssegers\Agent\Agent;
use App\Data\Blog\GroupPost;
use App\Data\Blog\Post;
use App\Data\Blog\MonthlyTheme;
use Auth;
use App;
use Redirect;
use DateTime;
use App\Data\Blog\Category;
use App\Data\System\User;
use App\Data\Blog\GroupSetting;
use Illuminate\Support\Facades\DB;
use App\Data\Blog\CategoryFilter;
use App\Data\Blog\PostSeries;
use App\Data\Blog\PostCategory;
use App\Data\Blog\UserPostLanguage;
use App\Data\Blog\GroupUser;
use App\Data\Blog\PostView;
use App\Data\Blog\Stock;
use App\Data\Blog\PostHelpful;
use App\Data\Blog\MonthlyThemeSubject;
use Event;
use App\Data\Blog\Wiki;
use wataridori\BiasRandom\BiasRandom;
use Cache;

class PostService
{
    CONST SELECT_FILTER = 1;
    CONST FILTER_BY_STOCK = 2;
    CONST FILTER_BY_THIS_MONTH = 3;
    CONST FILTER_BY_LAST_MONTH = 4;
    CONST FILTER_BY_THIS_MONTH_STRING = 'filter_this_month';
    CONST FILTER_BY_LAST_MONTH_STRING = 'filter_last_month';
    CONST ORDER_BY_PUBLISHED_AT = 'published_at';
    CONST PER_PAGE = 12;
    CONST ADMIN_PER_PAGE = 200;
    CONST UPDATE_POSTS_COUNT_NO = 0;
    CONST UPDATE_POSTS_COUNT_ONLY_CHANGED = 1;
    CONST UPDATE_POSTS_COUNT_ALL = 2;

    CONST WALL_FEED = 'follow';
    CONST WALL_ALL = 'all';
    CONST WALL_STOCK = 'clip';
    CONST WALL_MY_POSTS = 'myPosts';
    CONST WALL_RECENT = 'top_clips';
    CONST WALL_TOP = 'top_posts';
    CONST WALL_HELPFUL = 'helpful';
    CONST POST_PER_HOME_PAGE = 8;

    CONST PER_ALL_PAGE = 15;

    CONST CATE_POST = 9;
    CONST CATE_TOTAL_WORDS = 63;

    CONST FIRST_HALF = 1;
    CONST SECOND_HALF = 2;

    CONST NOT_SEE_DRAFT = 0;
    CONST SEE_DRAFT = 1;

    CONST FILTER_ALL = 'all';
    CONST FILTER_FRAMGIA = 'framgia';
    CONST FILTER_OTHERS = 'others';

    public static $array_wall = [self::WALL_RECENT, self::WALL_FEED, self::WALL_TOP, self::WALL_HELPFUL];

    /**
     * @param array $input
     * @return Post
     */
    public static function create($input)
    {
        $post = new Post;
        $post->user_id = $input['user_id'];
        $post->title = $input['title'];
        $post->content = $input['content'];
        $post->thumbnail = self::getThumbnail($input['content']);
        $post->language_code = $input['language_code'];
        $post->share_by_url = $input['share_by_url'];
        $post->monthly_theme_id = $input['monthly_theme_id'];
        $post->published_at = isset($input['publish']) ? new DateTime : null;
        if ($post->save()) {
            if ($input['group_id'] != 0 && !isset($input['autoSaveRunning'])) {
                $input['post_id'] = $post->id;
                GroupService::groupPostMaker($input);
            }

            App\Data\Blog\PostView::create([
                'post_id' => $post->id,
                'views_count' => 0,
            ]);

            if ($post->isPublished()) {
                Event::fire(App\Events\FollowingUserPostNotificationHandler::EVENT_NAME, $post);
            }

            return $post;

        } else {
            return Redirect::back()
                ->withInput($input)
                ->withErrors(['message', trans('messages.action_failed')]);
        }
    }

    /**
     * @param Post $post
     * @param array $input
     * @return mixed
     */
    public static function update($post, $input)
    {
        $unpublished = $post->publishedAt === null;
        $publishedAt = (isset($input['publish']) && !$post->publishedAt) ? new DateTime : $post->publishedAt;
        $data = [
            'title' => $input['title'],
            'content' => $input['content'],
            'thumbnail' => self::getThumbnail($input['content']),
            'language_code' => $input['language_code'],
            'share_by_url' => isset($input['share_by_url']) ? $input['share_by_url'] : self::NOT_SEE_DRAFT,
            'monthly_theme_id' => $input['monthly_theme_id'],
            'published_at' => $publishedAt,
        ];
        $result = $post->update($data);
        if ($result) {
            self::postUpdateCategories($post, $unpublished, $input);
        }
        return $result;
    }

    public static function postUpdateCategories($post, $unpublished, $input)
    {
        if (isset($input['publish'])) {
            if ($unpublished) {
                self::updateCategories($post, $input['category'], self::UPDATE_POSTS_COUNT_ALL);
            } else {
                self::updateCategories($post, $input['category'], self::UPDATE_POSTS_COUNT_ONLY_CHANGED);
            }
        } else {
            self::updateCategories($post, $input['category'], self::UPDATE_POSTS_COUNT_NO);
        }
        if (!isset($input['autoSaveRunning'])) {
            $input['post_id'] = $post->id;
            GroupService::groupPostMaker($input);
        }
        if ($unpublished && $post->isPublished()) {
            Event::fire(App\Events\FollowingUserPostNotificationHandler::EVENT_NAME, $post);
        }
    }

    /**
     * @param string|array $categories
     * @return string|array Categories ID
     */
    public static function parseCategoriesId($categories)
    {
        if (is_string($categories)) {
            $categories = explode(',', $categories);
        }

        $keep = Config::get('character.special_allowed');
        $categoriesId = [];
        foreach ($categories as $category) {
            $category = strip_tags($category);
            $shortName = convert_to_alias(convert_to_short_name($category, $keep));
            $cat = Category::where(['short_name' => $shortName])
                ->orWhere('name', $category)->first();

            if ($cat) {
                $categoriesId[] = $cat->id;
            } else {
                if (!empty($category)) {
                    $newCategory = Category::create([
                        'name' => $category,
                        'short_name' => $shortName,
                    ]);
                    $categoriesId[] = $newCategory->id;
                }
            }
        }

        $categoriesId = array_unique($categoriesId);

        return $categoriesId;
    }

    /**
     * @param Post $post
     * @param string|array $categories
     */
    public static function attachCategories($post, $categories)
    {
        $categoriesId = self::parseCategoriesId($categories);
        if (!empty($categoriesId)) {
            $post->categories()->attach($categoriesId);
            $post->increaseCategoryPostsCount();
        }
    }

    /**
     * Update list categories of a post.
     * @param Post $post
     * @param String $categories
     * @param int $updatePostCount
     */
    public static function updateCategories($post, $categories, $updatePostCount)
    {
        $updatedCategoriesId = self::parseCategoriesId($categories);
        if (!empty($updatedCategoriesId)) {
            if ($updatePostCount === self::UPDATE_POSTS_COUNT_ONLY_CHANGED || $updatePostCount === self::UPDATE_POSTS_COUNT_ALL) {
                Category::whereIn('id', $updatedCategoriesId)->increment('posts_count');
            }
        }
        $removedCategoriesId = self::getDetachCategories($post, $updatedCategoriesId);
        if (!empty($updatedCategoriesId)) {
            $post->categories()->sync($updatedCategoriesId);
        }
        if (!empty($removedCategoriesId)) {
            if ($updatePostCount === self::UPDATE_POSTS_COUNT_ONLY_CHANGED) {
                Category::whereIn('id', $removedCategoriesId)->decrement('posts_count');
            }
        }
    }


    public static function getDetachCategories($post, $updatedCategoriesId)
    {
        $postCategories = $post->postCategories()->get()->toArray();
        $removedCategoriesId = [];
        if (is_array($postCategories)) {
            foreach ($postCategories as $postCategory) {
                $index = array_search($postCategory['category_id'], $updatedCategoriesId);
                if ($index !== false) {
                    unset($updatedCategoriesId[$index]);
                } else {
                    $removedCategoriesId[] = $postCategory['category_id'];
                }
            }
        }
        return $removedCategoriesId;
    }

    public static function filterPosts($wall, $filterBy, $orderBy = self::ORDER_BY_PUBLISHED_AT)
    {
        $categoryFilterIds = CategoryFilter::lists('category_id');
        $postFilterIds = PostCategory::whereIn('category_id', $categoryFilterIds)->lists('post_id');

        switch ($wall) {
            case self::WALL_MY_POSTS:
                $posts = Post::where('user_id', Auth::id());
                break;
            case self::WALL_STOCK:
                if (Auth::user()) {
                    $posts = Auth::user()->stockPosts()->orderBy('stocks.created_at', 'desc');
                } else {
                    $posts = Post::where('posts.stocks_count', '>', 0);
                }
                break;
            case self::WALL_ALL:
                $posts = Post::whereNotIn('id', $postFilterIds);
                break;
            case self::WALL_FEED:
                $posts = Post::feed(Auth::id());
                break;
            default:
                $posts = Post::whereNotIn('id', $postFilterIds);
                break;
        }
        $posts = self::filterByUserLanguages($posts);
        $posts = self::filterPostsBy($filterBy, $posts);
        $posts = self::filterByGroupSecret($posts);
        $posts = $posts->whereNotNull('encrypted_id')->orderBy($orderBy, 'desc')->paginate(self::PER_PAGE);
        return $posts;
    }

    public static function getPostFollowInWall($wall, $pageCount = 0, $filterBy = self::SELECT_FILTER, $seoLang = '')
    {
        $recentStocksPosts = '';
        switch ($wall) {
            case self::WALL_MY_POSTS:
                $posts = Post::where('user_id', Auth::id());
                break;
            case self::WALL_STOCK:
                if (Auth::user()) {
                    $posts = Auth::user()
                        ->stockPosts()
                        ->orderBy('stocks.created_at', 'desc')
                        ->with('categories', 'user', 'user.profile', 'wiki', 'user.avatar');
                } else {
                    $posts = Post::where('posts.stocks_count', '>', 0);
                }
                break;
            case self::WALL_ALL:
                $posts = self::getPostSeries($pageCount, $filterBy);
                break;
            case self::WALL_FEED:
                $posts = Post::feed(Auth::id());
                break;
            case self::WALL_RECENT:
                $recentStocksPosts = Stock::orderBy('updated_at', 'desc');
                $posts = Post::with('categories', 'user', 'user.profile');
                break;
            case self::WALL_TOP:
                $posts = Post::with('categories', 'user', 'user.profile')
                    ->whereNull('deleted_at')
                    ->orderBy('views_count', 'desc');
                break;
            case self::WALL_HELPFUL:
                $posts = self::getHelpFullWall();
                break;
            default:
                $categoryFilterIds = CategoryFilter::lists('category_id');
                $postFilterIds = PostCategory::whereIn('category_id', $categoryFilterIds)->lists('post_id');
                $posts = Post::whereNotIn('id', $postFilterIds);
                $posts = self::filterByUserLanguages($posts);
                break;
        }
        $offset = $pageCount * self::PER_PAGE;
        if ($wall != self::WALL_ALL) {
            $posts = self::getPostNotWallAll($filterBy, $posts, $recentStocksPosts, $wall, $offset);
            if ($pageCount) {
                return $posts;
            }
        }
        $result = array();
        $result['class']['clip'] = '';
        $result['class']['all'] = '';
        $result['class']['follow'] = '';
        $result['class']['top_clips'] = '';
        $result['class']['top_posts'] = '';
        $result['class']['helpful'] = '';
        $result['class'][$wall] = 'selected';
        $result['class']['stock'] = 'hide';

        $result['wall'] = $wall;
        $result['seolang'] = $seoLang;
        $result['posts'] = $posts;
        $result['topStocked'] = self::getTopStockLastWeek();
        return $result;
    }

    private static function getHelpFullWall()
    {
        $postIdsHelpful = PostHelpful::select(
            'post_id',
            DB::raw('COUNT(IF(helpful, 1, NULL)) AS helpful_yes'),
            DB::raw('COUNT(IF(helpful, NULL, 1)) AS helpful_no')
        )->groupBy('post_id')->orderBy('helpful_yes', 'desc')->orderBy('helpful_no', 'asc')
            ->lists('post_id')
            ->toArray();

        $orderBy = isset($postIdsHelpful) && is_array($postIdsHelpful) && $postIdsHelpful != null ?
            implode(',', $postIdsHelpful) : '';

        $posts = Post::with('categories', 'user', 'user.profile')
            ->whereIn('id', $postIdsHelpful)
            ->orderByRaw(DB::raw('FIELD(id, ' . $orderBy . ')'));
        return $posts;
    }

    private static function getPostNotWallAll($filterBy, $posts, $recentStocksPosts, $wall, $offset)
    {
        $posts = self::filterPostsBy($filterBy, $posts, $recentStocksPosts);
        $posts = self::filterByGroupSecretNotInUser($posts, Auth::user());

        if (!in_array($wall, self::$array_wall)) {
            $posts = $posts->whereNotNull('published_at')
                ->whereNotNull('encrypted_id')
                ->orderBy('published_at', 'desc')
                ->take(self::PER_PAGE)
                ->skip($offset)
                ->get();
        } else {
            if ($wall != self::WALL_FEED) {
                $posts = self::filterByUserLanguages($posts);
            }
            $posts = $posts->whereNotNull('published_at')
                ->whereNotNull('encrypted_id')
                ->take(self::PER_PAGE)
                ->skip($offset)
                ->get();
        }
        return $posts;
    }

    public static function filterPostsBy($filterBy, $posts, $recentStocksPosts = '')
    {
        if ($filterBy == self::FILTER_BY_THIS_MONTH_STRING) {
            $filterBy = self::FILTER_BY_THIS_MONTH;
        }
        if ($filterBy == self::FILTER_BY_LAST_MONTH_STRING) {
            $filterBy = self::FILTER_BY_LAST_MONTH;
        }
        switch ($filterBy) {
            case self::FILTER_BY_THIS_MONTH:
                $startTime = Carbon::now()->firstOfMonth();
                $endTime = Carbon::now()->lastOfMonth();
                if (!empty($recentStocksPosts) && is_object($recentStocksPosts)) {
                    $recentStocksPosts = $recentStocksPosts->whereBetween('updated_at', [$startTime, $endTime]);
                }
                $posts = $posts->whereBetween('published_at', [$startTime, $endTime]);
                break;
            case self::FILTER_BY_LAST_MONTH:
                $startTime = Carbon::now()->subMonth()->firstOfMonth();
                $endTime = Carbon::now()->subMonth()->lastOfMonth();
                if (!empty($recentStocksPosts) && is_object($recentStocksPosts)) {
                    $recentStocksPosts = $recentStocksPosts->whereBetween('updated_at', [$startTime, $endTime]);
                }
                $posts = $posts->whereBetween('published_at', [$startTime, $endTime]);
                break;
        }
        if (!empty($recentStocksPosts) && is_object($recentStocksPosts)) {
            $posts = $posts->whereIn('id', $recentStocksPosts->lists('post_id'))
                ->orderBy('stocks_count', 'desc');
        }
        return $posts;
    }

    /**
     * Check whether user can create new post or not
     * @return bool
     */
    public static function checkDraftAvailable()
    {
        $draftsCount = Auth::user()->posts()->drafted()->count();
        $draftsLimit = Config::get('limitation.max_drafts');
        return $draftsCount < $draftsLimit;
    }

    public static function getPostForHomePage()
    {
        $posts = Post::whereNotNull('encrypted_id');
        self::filterByUserLanguages($posts);
        return Post::whereNotNull('encrypted_id')->orderBy('published_at', 'desc')->take(self::POST_PER_HOME_PAGE)->get();
    }

    public static function getComments($postId, $limit, $offset)
    {
        $post = Post::find((int)$postId);
        if (is_null($post) || empty($post)) {
            return false;
        } else {
            $comments = $post->getComments($limit, $offset);
            return $comments;
        }
    }

    public static function getTopStockLastWeek()
    {
        $topStocks = Post::getStockRankingByPost(Post::TOP_RANDOM_STOCKED);
        if (count($topStocks) <= Post::TOP_STOCKED_LIMIT) {
            return $topStocks;
        } else {
            $biasRandom = new BiasRandom();
            foreach ($topStocks as $key => $topstock) {
                $biasRandom->addElement($key, $topstock->stocksCount);
            }
            $topIds = $biasRandom->random(Post::TOP_STOCKED_LIMIT);
            $stocks = [];
            foreach ($topIds as $topId) {
                $stocks[] = $topStocks[$topId];
            }
            return $stocks;
        }
    }

    public static function getTopUsers($forFooter = false)
    {
        $cache_key = 'top_user';
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }
        $topUserLimit = $forFooter ? User::TOP_USERS_FOOTER_LIMIT : User::TOP_USERS_LIMIT;
        $users = User::getTopUsers();
        $count = count($users);

        if ($count > $topUserLimit) {
            $biasRandom = new BiasRandom();
            foreach ($users as $user) {
                $biasRandom->addElement($user->id, $user->stocked_number + 1);
            }
            $topIds = $biasRandom->random($topUserLimit);
            $users = User::with('avatar')
                ->whereIn('id', $topIds)
                ->get();
        }
        Cache::put($cache_key, $users, 1440);
        return $users;
    }

    public static function getOptionsSearch()
    {
        return [
            '' => trans('messages.post.choose'),
            Post::FIND_BY_POST => trans('messages.post.byPost'),
            Post::FIND_BY_CATEGORY => trans('messages.post.byCategory'),
        ];
    }

    public static function searchBy($input)
    {
        $posts = self::getPostFromFilter($input);

        $keyword = $input['name'];

        switch ($input['type']) {
            case Post::FIND_BY_POST:
                $posts = $posts->where('title', 'LIKE', '%' . $keyword . '%');
                break;
            case Post::FIND_BY_CATEGORY:
                $postIds = self::getPostsIdByCategories($keyword);
                $posts = $posts->whereIn('id', $postIds);
                break;

            default:
                $postIds = self::getPostsIdByCategories($keyword);
                $posts = $posts->where(function ($query) use ($keyword, $postIds) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhereIn('id', $postIds);
                });
                break;
        }

        $posts = $posts->orderBy('published_at', 'desc')
            ->whereNotNull('encrypted_id')
            ->paginate(PostService::ADMIN_PER_PAGE);

        return $posts;
    }

    public static function getFiltersInAllPage($filter, $filterBy = self::SELECT_FILTER)
    {
        switch ($filter) {
            case 'recently_clipped_posts':
                $recentStocksPosts = Stock::orderBy('updated_at', 'desc');
                switch ($filterBy) {
                    case self::FILTER_BY_THIS_MONTH_STRING:
                        $startTime = Carbon::now()->firstOfMonth();
                        $endTime = Carbon::now()->lastOfMonth();
                        $recentStocksPosts = $recentStocksPosts->whereBetween('updated_at', [$startTime, $endTime]);
                        break;
                    case self::FILTER_BY_LAST_MONTH_STRING:
                        $startTime = Carbon::now()->subMonth()->firstOfMonth();
                        $endTime = Carbon::now()->subMonth()->lastOfMonth();
                        $recentStocksPosts = $recentStocksPosts->whereBetween('updated_at', [$startTime, $endTime]);
                        break;
                }
                $orderBy = implode(',', $recentStocksPosts->lists('post_id'));
                $posts = Post::whereIn('id', $recentStocksPosts->lists('post_id'))
                    ->orderByRaw(DB::raw('FIELD(id, ' . $orderBy . ')'))
                    ->whereNotNull('published_at')
                    ->whereNotNull('encrypted_id');
                break;
            case 'top':
                $postsRanking = Post::whereNotNull('published_at')
                    ->whereNotNull('encrypted_id')
                    ->whereNull('deleted_at');

                if ($postsRanking->count() > 0) {
                    $postsRanking->select('id', DB::raw('views_count / POW(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(published_at), ' . Post::AGE_GRAVITY_FOR_RANKING . ') as rank_order'))
                        ->orderBy('rank_order', 'desc');
                }

                $postsRanking->take(Config::get('limitation.posts_limit_for_ranking'));
                switch ($filterBy) {
                    case self::FILTER_BY_THIS_MONTH_STRING:
                        $startTime = Carbon::now()->firstOfMonth();
                        $endTime = Carbon::now()->lastOfMonth();
                        $postsRanking = $postsRanking->whereBetween('published_at', [$startTime, $endTime]);
                        break;
                    case self::FILTER_BY_LAST_MONTH_STRING:
                        $startTime = Carbon::now()->subMonth()->firstOfMonth();
                        $endTime = Carbon::now()->subMonth()->lastOfMonth();
                        $postsRanking = $postsRanking->whereBetween('published_at', [$startTime, $endTime]);
                        break;
                }
                $orderBy = implode(',', $postsRanking->lists('id'));
                $posts = Post::whereIn('id', $postsRanking->lists('id'))
                    ->orderByRaw(DB::raw('FIELD(id, ' . $orderBy . ')'))
                    ->whereNotNull('published_at')
                    ->whereNotNull('encrypted_id');
                break;
            case 'helpful':
                $postIdsHelpful = PostHelpful::select('post_id',
                    DB::raw('COUNT(IF(helpful, 1, NULL)) AS helpful_yes'),
                    DB::raw('COUNT(IF(helpful, NULL, 1)) AS helpful_no'))
                    ->groupBy('post_id')
                    ->orderBy('helpful_yes', 'desc')
                    ->orderBy('helpful_no', 'asc')
                    ->lists('post_id');
                $orderBy = implode(',', $postIdsHelpful);
                $posts = Post::whereIn('id', $postIdsHelpful)
                    ->orderByRaw(DB::raw('FIELD(id, ' . $orderBy . ')'))
                    ->whereNotNull('published_at')
                    ->whereNotNull('encrypted_id');
                $posts = self::filterPostsBy($filterBy, $posts);
                break;
            case 'follow':
                $posts = Post::feed(Auth::id());
                break;
            default:
                $posts = Post::whereNotNull('published_at')->whereNotNull('encrypted_id')->orderBy('published_at', 'desc');
                break;
        }

        if ($filter != 'follow') {
            $posts = self::filterByUserLanguages($posts);
            $posts = self::filterByGroupSecret($posts);
        }

        return $posts;
    }

    public static function statisticAll($filter = self::FILTER_ALL)
    {
        if ($filter != self::FILTER_ALL) {
            $postsIdFromFramgia = self::getPostsIdFromFramgia();
        } else {
            $postsIdFromFramgia = array();
        }

        $total = Post::withTrashed()
            ->whereNotNull('encrypted_id');
        $published = Post::whereNotNull('published_at')
            ->whereNotNull('encrypted_id');
        $drafts = Post::whereNull('published_at')
            ->whereNotNull('encrypted_id');
        $deletedPublishedPosts = Post::withTrashed()
            ->whereNotNull('deleted_at')
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id');
        $deletedDrafts = Post::withTrashed()
            ->whereNotNull('deleted_at')
            ->whereNull('published_at')
            ->whereNotNull('encrypted_id');

        return self::filterStatisticAll($filter, $total, $published, $drafts, $deletedPublishedPosts, $deletedDrafts, $postsIdFromFramgia);
    }

    private static function filterStatisticAll($filter, $total, $published, $drafts, $deletedPublishedPosts, $deletedDrafts, $postsIdFromFramgia)
    {
        switch ($filter) {
            case self::FILTER_ALL:
                $total = $total->count();
                $published = $published->count();
                $drafts = $drafts->count();
                $deletedPublishedPosts = $deletedPublishedPosts->count();
                $deletedDrafts = $deletedDrafts->count();
                break;
            case self::FILTER_FRAMGIA:
                $total = $total->whereIn('id', $postsIdFromFramgia)->count();
                $published = $published->whereIn('id', $postsIdFromFramgia)->count();
                $drafts = $drafts->whereIn('id', $postsIdFromFramgia)->count();
                $deletedPublishedPosts = $deletedPublishedPosts->whereIn('id', $postsIdFromFramgia)->count();
                $deletedDrafts = $deletedDrafts->whereIn('id', $postsIdFromFramgia)->count();
                break;
            case self::FILTER_OTHERS:
                $total = $total->whereNotIn('id', $postsIdFromFramgia)->count();
                $published = $published->whereNotIn('id', $postsIdFromFramgia)->count();
                $drafts = $drafts->whereNotIn('id', $postsIdFromFramgia)->count();
                $deletedPublishedPosts = $deletedPublishedPosts->whereNotIn('id', $postsIdFromFramgia)->count();
                $deletedDrafts = $deletedDrafts->whereNotIn('id', $postsIdFromFramgia)->count();
                break;

            default:
                $total = $total->count();
                $published = $published->count();
                $drafts = $drafts->count();
                $deletedPublishedPosts = $deletedPublishedPosts->count();
                $deletedDrafts = $deletedDrafts->count();
                break;
        }
        return [
            'total' => $total,
            'published' => $published,
            'drafts' => $drafts,
            'deletedPublished' => $deletedPublishedPosts,
            'deletedDrafts' => $deletedDrafts,
        ];
    }

    public static function statisticInMonth($month, $year, $filter)
    {
        if ($filter != self::FILTER_ALL) {
            $postsIdFromFramgia = self::getPostsIdFromFramgia();
        } else {
            $postsIdFromFramgia = array();
        }

        $totalPosts = Post::withTrashed()->select(DB::raw('extract(day from created_at) as day'),
            DB::raw('count(*) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month)
            ->whereNotNull('encrypted_id')
            ->groupBy(DB::raw('extract(day from created_at)'));
        $deletedPublishedPosts = Post::withTrashed()->select(DB::raw('extract(day from created_at) as day'),
            DB::raw('count(*) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month)
            ->groupBy(DB::raw('extract(day from created_at)'))
            ->whereNotNull('deleted_at')
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id');
        $deletedDrafts = Post::withTrashed()->select(DB::raw('extract(day from created_at) as day'),
            DB::raw('count(*) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month)
            ->groupBy(DB::raw('extract(day from created_at)'))
            ->whereNotNull('deleted_at')
            ->whereNull('published_at')
            ->whereNotNull('encrypted_id');
        $publishedPosts = Post::select(DB::raw('extract(day from created_at) as day'),
            DB::raw('count(*) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month)
            ->groupBy(DB::raw('extract(day from created_at)'))
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id');
        $draftsPosts = Post::select(DB::raw('extract(day from created_at) as day'),
            DB::raw('count(*) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month)
            ->groupBy(DB::raw('extract(day from created_at)'))
            ->whereNull('published_at')
            ->whereNotNull('encrypted_id');
        $allClips = Stock::select(DB::raw('extract(day from created_at) as day'),
            DB::raw('count(*) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month)
            ->groupBy(DB::raw('extract(day from created_at)'));
        $postsCliped = Stock::select(DB::raw('extract(day from created_at) as day'),
            DB::raw('count(distinct post_id) as total'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('extract(month from created_at)'), $month)
            ->groupBy(DB::raw('extract(day from created_at)'));
        return self::filterStatisticInMonth($filter, $totalPosts, $deletedPublishedPosts, $postsIdFromFramgia,
            $deletedDrafts, $publishedPosts, $draftsPosts, $allClips, $postsCliped
        );
    }

    private static function filterStatisticInMonth($filter, $totalPosts, $deletedPublishedPosts, $postsIdFromFramgia,
        $deletedDrafts, $publishedPosts, $draftsPosts, $allClips, $postsCliped
    )
    {
        switch ($filter) {
            case self::FILTER_ALL:
                $totalPosts = $totalPosts->lists('total', 'day');
                $deletedPublishedPosts = $deletedPublishedPosts->lists('total', 'day');
                $deletedDrafts = $deletedDrafts->lists('total', 'day');
                $publishedPosts = $publishedPosts->lists('total', 'day');
                $draftsPosts = $draftsPosts->lists('total', 'day');
                $allClips = $allClips->lists('total', 'day');
                $postsCliped = $postsCliped->lists('total', 'day');
                break;
            case self::FILTER_FRAMGIA:
                $totalPosts = $totalPosts->whereIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $deletedPublishedPosts = $deletedPublishedPosts->whereIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $deletedDrafts = $deletedDrafts->whereIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $publishedPosts = $publishedPosts->whereIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $draftsPosts = $draftsPosts->whereIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $allClips = $allClips->whereIn('post_id', $postsIdFromFramgia)->lists('total', 'day');
                $postsCliped = $postsCliped->whereIn('post_id', $postsIdFromFramgia)->lists('total', 'day');
                break;
            case self::FILTER_OTHERS:
                $totalPosts = $totalPosts->whereNotIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $deletedPublishedPosts = $deletedPublishedPosts->whereNotIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $deletedDrafts = $deletedDrafts->whereNotIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $publishedPosts = $publishedPosts->whereNotIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $draftsPosts = $draftsPosts->whereNotIn('id', $postsIdFromFramgia)->lists('total', 'day');
                $allClips = $allClips->whereNotIn('post_id', $postsIdFromFramgia)->lists('total', 'day');
                $postsCliped = $postsCliped->whereNotIn('post_id', $postsIdFromFramgia)->lists('total', 'day');
                break;

            default:
                $totalPosts = $totalPosts->lists('total', 'day');
                $deletedPublishedPosts = $deletedPublishedPosts->lists('total', 'day');
                $deletedDrafts = $deletedDrafts->lists('total', 'day');
                $publishedPosts = $publishedPosts->lists('total', 'day');
                $draftsPosts = $draftsPosts->lists('total', 'day');
                $allClips = $allClips->lists('total', 'day');
                $postsCliped = $postsCliped->lists('total', 'day');
                break;
        }

        return [
            'total' => $totalPosts,
            'deletedPublished' => $deletedPublishedPosts,
            'deletedDrafts' => $deletedDrafts,
            'published' => $publishedPosts,
            'drafts' => $draftsPosts,
            'clips' => $allClips,
            'postsCliped' => $postsCliped,
        ];
    }

    public static function statisticByWeek($weeks, $year, $filter)
    {
        if ($weeks == self::FIRST_HALF) {
            $weeks_start = 1;
            $weeks_end = 26;
        } else {
            $weeks_start = 27;
            $weeks_end = 52;
        }

        if ($filter != self::FILTER_ALL) {
            $postsIdFromFramgia = self::getPostsIdFromFramgia();
        } else {
            $postsIdFromFramgia = array();
        }

        $totalPosts = Post::withTrashed()->select(
            DB::raw('count(*) as total'),
            DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
            DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->whereNotNull('encrypted_id')
            ->groupBy('week');
        $deletedPublishedPosts = Post::withTrashed()->select(
            DB::raw('count(*) as total'),
            DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
            DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->whereNotNull('deleted_at')
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->groupBy('week');
        $deletedDrafts = Post::withTrashed()->select(
            DB::raw('count(*) as total'),
            DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
            DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->whereNotNull('deleted_at')
            ->whereNull('published_at')
            ->whereNotNull('encrypted_id')
            ->groupBy('week');
        $publishedPosts = Post::select(
            DB::raw('count(*) as total'),
            DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
            DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->groupBy('week');
        $draftsPosts = Post::select(
            DB::raw('count(*) as total'),
            DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
            DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->whereNull('published_at')
            ->whereNotNull('encrypted_id')
            ->groupBy('week');
        $allClips = Stock::select(
            DB::raw('count(*) as total'),
            DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
            DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->groupBy('week');
        $postsCliped = Stock::select(
            DB::raw('count(distinct post_id) as total'),
            DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1) as week'),
            DB::raw('adddate(DATE_FORMAT(created_at, "%Y-%m-%d"), INTERVAL 2-DAYOFWEEK(DATE_FORMAT(created_at, "%Y-%m-%d")) DAY) as day_week_start'))
            ->where(DB::raw('extract(year from created_at)'), $year)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '>=', $weeks_start)
            ->where(DB::raw('week(DATE_FORMAT(created_at, "%Y-%m-%d"), 1)'), '<=', $weeks_end)
            ->groupBy('week');
        $result = self::filterStatisticByWeek($filter, $totalPosts, $deletedPublishedPosts, $deletedDrafts, $publishedPosts, $draftsPosts
            , $allClips, $postsCliped, $postsIdFromFramgia);
        $result['weeksStartDay'] = self::filterWeeksStartDay($weeks_start, $weeks_end, $totalPosts, $allClips);
        return $result;
    }

    private static function filterWeeksStartDay($weeks_start, $weeks_end, $totalPosts, $allClips)
    {
        $weeksStartDay = array();
        $weeksStartDay[] = '';
        for ($i = $weeks_start; $i <= $weeks_end; $i++) {
            $weeksStartDay[$i] = '';
            foreach ($totalPosts as $totalPost) {
                if ($i == $totalPost->week) {
                    $weeksStartDay[$i] = $totalPost->day_week_start;
                }
            }
            foreach ($allClips as $allClip) {
                if ($i == $allClip->week) {
                    $weeksStartDay[$i] = $allClip->day_week_start;
                }
            }
        }
        return $weeksStartDay;
    }

    private static function filterStatisticByWeek($filter, $totalPosts, $deletedPublishedPosts, $deletedDrafts, $publishedPosts,
        $draftsPosts, $allClips, $postsCliped, $postsIdFromFramgia)
    {
        switch ($filter) {
            case self::FILTER_ALL:
                $totalPosts = $totalPosts->get();
                $deletedPublishedPosts = $deletedPublishedPosts->get();
                $deletedDrafts = $deletedDrafts->get();
                $publishedPosts = $publishedPosts->get();
                $draftsPosts = $draftsPosts->get();
                $allClips = $allClips->get();
                $postsCliped = $postsCliped->get();
                break;
            case self::FILTER_FRAMGIA:
                $totalPosts = $totalPosts->whereIn('id', $postsIdFromFramgia)->get();
                $deletedPublishedPosts = $deletedPublishedPosts->whereIn('id', $postsIdFromFramgia)->get();
                $deletedDrafts = $deletedDrafts->whereIn('id', $postsIdFromFramgia)->get();
                $publishedPosts = $publishedPosts->whereIn('id', $postsIdFromFramgia)->get();
                $draftsPosts = $draftsPosts->whereIn('id', $postsIdFromFramgia)->get();
                $allClips = $allClips->whereIn('post_id', $postsIdFromFramgia)->get();
                $postsCliped = $postsCliped->whereIn('post_id', $postsIdFromFramgia)->get();
                break;
            case self::FILTER_OTHERS:
                $totalPosts = $totalPosts->whereNotIn('id', $postsIdFromFramgia)->get();
                $deletedPublishedPosts = $deletedPublishedPosts->whereNotIn('id', $postsIdFromFramgia)->get();
                $deletedDrafts = $deletedDrafts->whereNotIn('id', $postsIdFromFramgia)->get();
                $publishedPosts = $publishedPosts->whereNotIn('id', $postsIdFromFramgia)->get();
                $draftsPosts = $draftsPosts->whereNotIn('id', $postsIdFromFramgia)->get();
                $allClips = $allClips->whereNotIn('post_id', $postsIdFromFramgia)->get();
                $postsCliped = $postsCliped->whereNotIn('post_id', $postsIdFromFramgia)->get();
                break;

            default:
                $totalPosts = $totalPosts->get();
                $deletedPublishedPosts = $deletedPublishedPosts->get();
                $deletedDrafts = $deletedDrafts->get();
                $publishedPosts = $publishedPosts->get();
                $draftsPosts = $draftsPosts->get();
                $allClips = $allClips->get();
                $postsCliped = $postsCliped->get();
                break;
        }
        return [
            'total' => $totalPosts,
            'deletedPublished' => $deletedPublishedPosts,
            'deletedDrafts' => $deletedDrafts,
            'published' => $publishedPosts,
            'drafts' => $draftsPosts,
            'clips' => $allClips,
            'postsCliped' => $postsCliped,
        ];
    }

    public static function getWeeksOption()
    {
        return [
            self::FIRST_HALF => trans('messages.post.weeks') . ' 1 - 26',
            self::SECOND_HALF => trans('messages.post.weeks') . ' 27 - 52',
        ];
    }

    public static function filterByUserLanguages($posts)
    {
        $userLanguages = UserPostLanguage::getCurrentUserLanguages();
        if (isset($userLanguages[0]) && $userLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $posts = $posts->whereIn('language_code', $userLanguages);
        }
        return $posts;
    }

    public static function filterByUserLanguagesTheme($posts)
    {
        if (Auth::check()) {
            $userLanguages = UserPostLanguage::getCurrentUserLanguages();
            if (isset($userLanguages[0]) && $userLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                $posts = $posts->whereIn('language_code', $userLanguages);
            }
        } else {
            $sysLang = App::getLocale();
            if (isset($sysLang)) {
                $postLang = array($sysLang);
                $posts = $posts->whereIn('language_code', $postLang);
            }
        }

        return $posts;
    }

    public static function getPostByThemeCategory($categoryId, $pageCount = 0)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $posts = Post::with('categories', 'user.avatar')->where('monthly_theme_id', $categoryId)->whereNotIn('id', $unapprovedPosts);
        $posts = self::filterByUserLanguagesTheme($posts);

        $offset = $pageCount * self::PER_PAGE;
        $posts = $posts->whereNotNull('published_at')->whereNotNull('encrypted_id')->orderBy('published_at', 'desc')->take(self::PER_PAGE)->skip($offset);
        return $posts;
    }

    public static function getPostByThemeCategoryCount($categoryId)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $posts = Post::with('categories', 'user.avatar')->where('monthly_theme_id', $categoryId)->whereNotIn('id', $unapprovedPosts);
        $posts = self::filterByUserLanguagesTheme($posts);
        $posts = $posts->whereNotNull('published_at')->whereNotNull('encrypted_id')->orderBy('published_at', 'desc');
        return $posts->count();
    }

    public static function getPostByThemeCategoryStock($categoryId, $pageCount = 0)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $posts = Post::with('categories', 'user.avatar')->where('monthly_theme_id', $categoryId)->whereNotIn('id', $unapprovedPosts);
        $posts = self::filterByUserLanguagesTheme($posts);

        $offset = $pageCount * self::PER_PAGE;
        $posts = $posts->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->orderBy('stocks_count', 'desc')
            ->orderBy('published_at', 'desc')
            ->take(self::PER_PAGE)->skip($offset);
        return $posts;
    }

    public static function getPostByThemeCategoryStockCount($categoryId)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $posts = Post::with('categories', 'user.avatar')->where('monthly_theme_id', $categoryId)->whereNotIn('id', $unapprovedPosts);
        $posts = self::filterByUserLanguagesTheme($posts);
        $posts = $posts->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->orderBy('stocks_count', 'desc')
            ->orderBy('published_at', 'desc');
        return $posts->count();
    }

    public static function getPostByThemeCategoryTop($categoryId, $pageCount = 0)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $postsInCategory = Post::where('monthly_theme_id', $categoryId)
            ->whereNotIn('posts.id', $unapprovedPosts)
            ->whereNotNull('posts.encrypted_id')
            ->whereNull('posts.deleted_at')
            ->whereNotNull('posts.published_at')
            ->orderBy('posts.published_at', 'desc')
            ->lists('posts.id');

        $postsRanking = Post::select('id', DB::raw('views_count / POW(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(published_at), ' . Post::AGE_GRAVITY_FOR_RANKING . ') as rank_order'))
            ->whereIn('id', $postsInCategory)
            ->whereNotNull('encrypted_id')
            ->whereNull('deleted_at')
            ->orderBy('rank_order', 'desc');
        $posts = Post::with('categories', 'user.avatar', 'user.profile')
            ->whereIn('id', $postsRanking->lists('id'))
            ->orderBy('posts.views_count', 'desc');

        $posts = self::filterByUserLanguagesTheme($posts);
        $offset = $pageCount * self::PER_PAGE;
        $posts = $posts->take(self::PER_PAGE)->skip($offset);
        return $posts;
    }

    public static function getPostByThemeCategoryTopCount($categoryId)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $postsInCategory = Post::where('monthly_theme_id', $categoryId)
            ->whereNotIn('posts.id', $unapprovedPosts)
            ->whereNotNull('posts.encrypted_id')
            ->whereNull('posts.deleted_at')
            ->whereNotNull('posts.published_at')
            ->orderBy('posts.published_at', 'desc')
            ->lists('posts.id');

        $postsRanking = Post::select('id', DB::raw('views_count / POW(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(published_at), ' . Post::AGE_GRAVITY_FOR_RANKING . ') as rank_order'))
            ->whereIn('id', $postsInCategory)
            ->whereNotNull('encrypted_id')
            ->whereNull('deleted_at')
            ->orderBy('rank_order', 'desc');
        $posts = Post::with('categories', 'user.avatar', 'user.profile')
            ->whereIn('id', $postsRanking->lists('id'))
            ->orderBy('posts.views_count', 'desc');

        $posts = self::filterByUserLanguagesTheme($posts);
        return $posts->count();
    }

    public static function getPostByThemeCategoryHelpful($categoryId, $pageCount = 0)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $postsInCategory = Post::where('monthly_theme_id', $categoryId)
            ->whereNotIn('posts.id', $unapprovedPosts)
            ->whereNotNull('posts.encrypted_id')
            ->whereNull('posts.deleted_at')
            ->whereNotNull('posts.published_at')
            ->orderBy('posts.published_at', 'desc')
            ->lists('posts.id');

        $postsRanking = Post::select('id', DB::raw('(SELECT COUNT(*) FROM post_helpfuls WHERE post_helpfuls.post_id = posts.id) as helpful_order'))
            ->whereIn('id', $postsInCategory)
            ->whereNotNull('encrypted_id')
            ->whereNull('deleted_at')
            ->orderBy('helpful_order', 'desc');
        $posts = Post::with('categories', 'user.avatar', 'user.profile')
            ->whereIn('id', $postsRanking->lists('id'))
            ->orderBy('posts.published_at', 'desc');

        $posts = self::filterByUserLanguagesTheme($posts);
        $offset = $pageCount * self::PER_PAGE;
        $posts = $posts->take(self::PER_PAGE)->skip($offset);
        return $posts;
    }

    public static function getPostByThemeCategoryHelpfulCount($categoryId)
    {
        $unapprovedPosts = GroupPost::where('approved', 0)->whereNotNull('post_id')->lists('post_id');
        $postsInCategory = Post::where('monthly_theme_id', $categoryId)
            ->whereNotIn('posts.id', $unapprovedPosts)
            ->whereNotNull('posts.encrypted_id')
            ->whereNull('posts.deleted_at')
            ->whereNotNull('posts.published_at')
            ->orderBy('posts.published_at', 'desc')
            ->lists('posts.id');

        $postsRanking = Post::select('id', DB::raw('(SELECT COUNT(*) FROM post_helpfuls WHERE post_helpfuls.post_id = posts.id) as helpful_order'))
            ->whereIn('id', $postsInCategory)
            ->whereNotNull('encrypted_id')
            ->whereNull('deleted_at')
            ->orderBy('helpful_order', 'desc');
        $posts = Post::with('categories', 'user.avatar', 'user.profile')
            ->whereIn('id', $postsRanking->lists('id'))
            ->orderBy('posts.published_at', 'desc');

        $posts = self::filterByUserLanguagesTheme($posts);
        return $posts->count();
    }

    public static function getThemesOption($themeId)
    {
        $monthlyThemeSubjects = MonthlyThemeSubject::orderBy('publish_year', 'desc')
            ->orderBy('publish_month', 'desc')
            ->lists('theme_name', 'id');
        if (!is_null($themeId) && !empty($themeId)) {
            $monthlyThemeSubjectId = MonthlyTheme::where('id', $themeId)->first()->monthly_theme_subject_id;
            $monthlyThemes = MonthlyTheme::where('monthly_theme_subject_id', $monthlyThemeSubjectId)->get();
            $monthlyThemeLanguages = [];
            foreach ($monthlyThemes as $key => $monthlyTheme) {
                $monthlyThemeLanguage = $monthlyTheme->themeLanguages()->first();
                $monthlyThemeLanguages[$monthlyThemeLanguage->monthly_theme_id] = $monthlyThemeLanguage->name;
            }
        }
        return [
            'monthlyThemeSubjects' => $monthlyThemeSubjects,
            'monthlyThemeLanguages' => isset($monthlyThemeLanguages) ? $monthlyThemeLanguages : null,
            'monthlyThemeSubjectId' => isset($monthlyThemeSubjectId) ? $monthlyThemeSubjectId : null,
            'themeId' => $themeId,
        ];
    }

    public static function getShareDraftOption()
    {
        return [
            self::NOT_SEE_DRAFT => trans('labels.not_see_draft'),
            self::SEE_DRAFT => trans('labels.see_draft'),
        ];
    }

    public static function getPopularPosts($limit = Post::POPULAR_POST_LIMIT_IN_POST_INDEX, $language = null)
    {
        $lastMonthObject = new Carbon('last month');
        $lastMonth = $lastMonthObject->month;

        $postViewCountIds = PostView::whereMonth('created_at', '=', Carbon::now()->month)
            ->orWhere(DB::raw('MONTH(created_at)', '=', $lastMonth))
            ->groupBy('post_id')
            ->limit(Post::POPULAR_POST_RAMDOM)
            ->orderBy('views_count', 'desc')
            ->lists('post_id');
    
        $posts = Post::with('user', 'categories')
            ->whereIn('id', $postViewCountIds)
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->whereNotIn('id', function ($queryGroupPost) {
                $queryGroupPost->select('post_id')
                    ->from('group_posts')
                    ->whereIn('group_posts.group_id', function ($queryGroupSetting) {
                        $queryGroupSetting->select('group_settings.group_id')
                            ->from('group_settings')
                            ->where('privacy_flag', GroupSetting::PRIVACY_PRIVATE);
                    })
                    ->whereNotNull('post_id');
            });

        if (is_array($language) && count($language) > 0) {
            if ($language[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                $posts->whereIn('language_code', $language);
            }
        } else {
            $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();
            if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                $posts->whereIn('language_code', $currentUserLanguages);
            }
        }

        $posts = $posts->take(Post::POPULAR_POST_RAMDOM)->get();

        if ($posts->count() > $limit) {
            $posts = $posts->random($limit);
        }

        return $posts;
    }

    public static function filterByGroupSecret($posts)
    {
        $secretGroupsId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)->lists('group_id');
        $secretPostsId = GroupPost::whereIn('group_id', $secretGroupsId)
            ->whereNotNull('post_id')
            ->lists('post_id');
        $posts = $posts->whereNotIn('posts.id', $secretPostsId);
        return $posts;
    }

    public static function filterByGroupSecretNotInUser($posts, $user, $wall = '')
    {
        if (Auth::check()) {
            $groupUsers = GroupUser::where('user_id', $user->id)->lists('group_id');
            $secretGroupsId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)
                ->whereNotIn('group_id', $groupUsers)
                ->lists('group_id');
        } else {
            $secretGroupsId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)->lists('group_id');
        }
        $secretPostsId = GroupPost::whereIn('group_id', $secretGroupsId)
            ->whereNotNull('post_id')
            ->lists('post_id');
        if (empty($wall)) {
            $posts = $posts->whereNotIn('posts.id', $secretPostsId);
        } elseif ($wall == self::WALL_ALL) {
            $secretSeriesId = GroupPost::whereIn('group_id', $secretGroupsId)
                ->whereNotNull('group_series_id')
                ->lists('group_series_id');

            $posts = $posts->where(function ($query) use ($secretPostsId, $secretSeriesId) {
                return $query->where(function ($firstQuery) use ($secretPostsId) {
                    return $firstQuery->whereNotIn('post_id', $secretPostsId)
                        ->where('group_series_id', 0);
                })
                    ->orWhere(function ($secondQuery) use ($secretSeriesId) {
                        return $secondQuery->whereNotIn('group_series_id', $secretSeriesId)
                            ->where('post_id', 0);
                    });
            });
        }

        return $posts;
    }

    public static function getPopularPostsMagazine($limit = Post::POPULAR_POST_LIMIT_IN_POST_INDEX, $language = null)
    {
        $lastMonthObject = new Carbon('last month');
        $lastMonth = $lastMonthObject->month;

        $postViewCountIds = PostView::whereMonth('created_at', '=', Carbon::now()->month)
            ->orWhere(DB::raw('MONTH(created_at)', '=', $lastMonth))
            ->groupBy('post_id')
            ->limit(Post::POPULAR_POST_RAMDOM)
            ->orderBy('views_count', 'desc')
            ->lists('post_id');

        $posts = Post::with('user', 'categories')
            ->whereIn('id', $postViewCountIds)
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->whereNotIn('id', function ($queryGroupPost) {
                $queryGroupPost->select('post_id')
                    ->from('group_posts')
                    ->whereIn('group_posts.group_id', function ($queryGroupSetting) {
                        $queryGroupSetting->select('group_settings.group_id')
                            ->from('group_settings')
                            ->where('privacy_flag', GroupSetting::PRIVACY_PUBLIC);
                    })
                    ->whereNotNull('post_id');
            });

        if (is_array($language) && count($language) > 0) {
            if ($language[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                $posts->whereIn('language_code', $language);
            }
        } else {
            $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();
            if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
                $posts->whereIn('language_code', UserPostLanguage::getCurrentUserLanguages());
            }
        }

        $posts = $posts->take(Post::POPULAR_POST_RAMDOM)->get();

        if ($posts->count() > $limit) {
            $posts = $posts->random($limit);
        }

        return $posts;
    }

    public static function getThumbnail($content)
    {
        preg_match('/\!\[.*\]\((.*)\)/', $content, $thumbnail);
        $result = '';
        if (isset($thumbnail[1])) {
            preg_match('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $thumbnail[1], $link);
            $result = (isset($link[0])) ? $link[0] : '';
            if (empty($result)) {
                $arrMk = explode(' ', $thumbnail[1]);
                $result = isset($arrMk[0]) ? $arrMk[0] : '';
            }
        }
        if ($result != '') {
            $result = self::getThumbnailHttp($result);
        }
        if ($result == '') {
            $result = self::getThumbnailBase64($content);
        }
        return $result;
    }

    private static function getThumbnailHttp($result)
    {
        $explodeResult = explode('/', $result);
        $user = Auth::user();
        if (!in_array($explodeResult[0], ['http:', 'https:'])) {
            $image = new ImageService($user);
            $result = $image->createPostThumb(end($explodeResult));
        } else {
            if (is_string($result)) {
                $thumb = new ImageService($user);
                $image = $thumb->saveImageByUrl($result);
                if (isset($image->name)) {
                    $result = $thumb->createPostThumb($image->name);
                }
            }
        }
        return $result;
    }

    private static function getThumbnailBase64($content)
    {
        $result = '';
        preg_match('/(<img[^>]+>)/i', $content, $img);
        if (isset($img[1])) {
            preg_match('/src="([^"]+)/i', $content, $src);
            if (isset($src[1]) && str_contains($src[1], 'data:image')) {
                $data = explode(',', $src[1]);
                if (isset($data[1])) {
                    $thumb = new ImageService(Auth::user());
                    $image = $thumb->saveImageBase64($data[1], $data[0]);
                    if (isset($image->name)) {
                        $result = $thumb->createPostThumb($image->name);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * change img src to data-origin
     * @param $input
     * @return mixed
     */
    public static function lazyLoadImg($input) {
        $output = preg_replace_callback( '/<img .*?>/', function($matches) {
            return preg_replace( '/\bsrc\s*=\s*[\'"](.*?)[\'"]/',
                'data-original="$1" src = "data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="', $matches[0] );
        }, $input );
        return self::newMarkdownForImage($output);
    }

    public static function newMarkdownForImage($content)
    {
        preg_match_all('/\!\[.*\]\((.*)\)/', $content, $imgsMkd);

        if (!empty($imgsMkd)) {
            foreach ($imgsMkd[1] as $key => $imgMkd) {
                $imgMkd = strip_tags($imgMkd);

                //get alt image
                $imgMkd0 = (isset($imgsMkd[0][$key])) ? $imgsMkd[0][$key] : '';
                preg_match('/\!\[(.*)\]/', $imgMkd0, $altImage);
                $altImg = (isset($altImage[1])) ? $altImage[1] : '';

                //get link href image
                preg_match('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $imgMkd, $link);
                $href = (isset($link[0])) ? $link[0] : '';
                if (empty($href)) {
                    $arrMk = explode(' ', $imgMkd);
                    $href = isset($arrMk[0]) ? $arrMk[0] : '';
                }

                //get size image
                $removeLink = str_replace($href, '', $imgMkd);
                preg_match('/\=(.*)/', $removeLink, $str2);
                $size = isset($str2[1]) ? $str2[1] : '';
                $arrSize = explode('x', $size);
                $width = isset($arrSize[0]) ? $arrSize[0] : '';
                $height = isset($arrSize[1]) ? $arrSize[1] : '';

                //get title image
                $title = str_replace('=' . $size, '', $removeLink);
                $title = str_replace('&quot;', '', $title);

                //Get image for device
                $agent = new Agent();
                if ($agent->isDesktop()) {
                    $type = ImageService::DEVICE_PC;
                } elseif ($agent->isMobile()) {
                    $type = ImageService::DEVICE_MOBILE;
                } elseif ($agent->isTablet()) {
                    $type = ImageService::DEVICE_TABLET;
                } else {
                    $type = ImageService::DEVICE_PC;
                }

                $href = HelperService::getImageBy($href, $type);
                $src = 'data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';

                $imgTag = '<img title="' . $title . '" src="' . $src . '" data-original="' . $href . '" alt="' . $altImg . '" height="' . $height . '" width="' . $width . '">';

                //replace new markdown by image tag
                $content = str_replace($imgMkd0, $imgTag, $content);
            }
        }

        return $content;
    }

    public static function getFilterOptions()
    {
        return [
            self::FILTER_ALL => trans('messages.post.filter_all'),
            self::FILTER_FRAMGIA => trans('messages.post.filter_framgia'),
            self::FILTER_OTHERS => trans('messages.post.filter_others'),
        ];
    }

    public static function getPostsIdFromFramgia()
    {
        $usersIdFramgia = UserService::getUsersIdFramgia();
        $postsIdFromFramgia = Post::withTrashed()
            ->whereIn('user_id', $usersIdFramgia)
            ->lists('id');

        return $postsIdFromFramgia;
    }

    public static function getParsedWiki($postWiki)
    {
        $childWiki = Wiki::with('post')
            ->where('group_id', $postWiki->wiki->group_id)
            ->whereNotNull('parent_id')
            ->get();

        $arrTitleToLink = [];
        $content = $postWiki->content;

        if (!is_null($childWiki)) {
            foreach ($childWiki as $wiki) {
                $arrTitleToLink[vn_to_latin($wiki->title)] = url_to_post($postWiki);
            }
        }

        preg_match_all('/\[\[.*\]\]/', $content, $links);

        foreach (array_unique($links[0]) as $link) {
            $originalLink = $link;

            $link = !empty($link) ? substr($link, 2) : '';
            $link = !empty($link) ? substr($link, 0, -2) : '';
            $link = vn_to_latin(html_entity_decode($link));
            $link = str_replace(']', '', $link);
            $link = str_replace(' ', '_', $link);
            $link = ucwords($link);

            $titleOriginal = str_replace(']', '', $originalLink);
            $titleOriginal = str_replace('[', '', $titleOriginal);

            $link = isset($arrTitleToLink[$link]) ? '<a href="' . $arrTitleToLink[$link] . '" >' . $titleOriginal . '</a>' : $titleOriginal;
            $content = str_replace($originalLink, $link, $content);
        }

        return $content;
    }

    public static function getListFilter()
    {
        $categoriesId = PostCategory::groupBy('category_id')->lists('category_id');
        $categories = Category::whereIn('id', $categoriesId)->lists('name', 'id');
        $usersId = Post::groupBy('user_id')->lists('user_id');
        $users = User::whereIn('id', $usersId)->lists('name', 'id');
        $languageCode = Post::whereNotNull('language_code')->groupBy('language_code')->lists('language_code');
        $language = [];
        foreach (Config::get('detect_language.code') as $code => $lang) {
            if ($languageCode->search($code)) {
                $language[$code] = $lang;
            }
        }
        return [
            'categories' => $categories,
            'authors' => $users,
            'language' => $language,
            'status' => [
                Post::STATUS_PUBLISHED => trans('labels.published'),
                Post::STATUS_DRAFT => trans('labels.draft'),
            ],
        ];
    }

    public static function getPostFromFilter($input)
    {
        if ($input['status'] == Post::STATUS_PUBLISHED) {
            $posts = Post::whereNotNull('published_at');
        } else {
            $posts = Post::whereNull('published_at');
        }

        if (isset($input['category'])) {
            $postCategoriesId = PostCategory::whereIn('category_id', $input['category'])->lists('post_id');
            $posts = $posts->whereIn('id', $postCategoriesId);
        }

        if (isset($input['author'])) {
            $posts = $posts->whereIn('user_id', $input['author']);
        }

        if (isset($input['language'])) {
            $posts = $posts->whereIn('language_code', $input['language']);
        }

        return $posts;
    }

    public static function getPostsIdByCategories($keyword)
    {
        $categories = Category::with('posts')
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('short_name', 'LIKE', '%' . $keyword . '%');
            })->get();

        $postIds = [];
        foreach ($categories as $category) {
            foreach ($category->posts as $post) {
                $postIds[] = $post->id;
            }
        }

        return $postIds;
    }

    public static function filterRssPosts($wall, $filterBy = self::SELECT_FILTER, $seoLang = '')
    {
        $recentStocksPosts = '';
        $posts = array();
        switch ($wall) {
            case self::WALL_ALL:
                $categoryFilterIds = CategoryFilter::lists('category_id');
                $postFilterIds = PostCategory::whereIn('category_id', $categoryFilterIds)
                    ->lists('post_id');
                $posts = Post::with('categories', 'user')
                    ->whereNotIn('id', $postFilterIds);
                if ($seoLang != PostService::WALL_ALL) {
                    $posts = self::filterByUserLanguages($posts);
                }
                break;
            case self::WALL_RECENT:
                $recentStocksPosts = Stock::orderBy('updated_at', 'desc');
                $posts = Post::with('categories', 'user', 'user.profile', 'wiki');
                break;
            case self::WALL_TOP:
                $posts = Post::with('categories', 'user', 'user.profile', 'wiki')
                    ->whereNull('deleted_at')
                    ->orderBy('views_count', 'desc');
                break;
            case self::WALL_HELPFUL:
                $postIdsHelpful = PostHelpful::select(
                    'post_id',
                    DB::raw('COUNT(IF(helpful, 1, NULL)) AS helpful_yes'),
                    DB::raw('COUNT(IF(helpful, NULL, 1)) AS helpful_no')
                )
                    ->groupBy('post_id')
                    ->orderBy('helpful_yes', 'desc')
                    ->orderBy('helpful_no', 'asc')
                    ->lists('post_id');
                $orderBy = implode(',', $postIdsHelpful);
                $posts = Post::with('categories', 'user', 'user.profile', 'wiki')
                    ->whereIn('id', $postIdsHelpful)
                    ->orderByRaw(DB::raw('FIELD(id, ' . $orderBy . ')'));
                break;
        }

        $posts = self::filterPostsBy($filterBy, $posts, $recentStocksPosts);
        $posts = self::filterByGroupSecretNotInUser($posts, Auth::user());

        if (!in_array($wall, self::$array_wall)) {
            $posts = $posts->whereNotNull('published_at')
                ->whereNotNull('encrypted_id')
                ->orderBy('published_at', 'desc')
                ->get();
        } else {
            if ($wall != self::WALL_FEED) {
                $posts = self::filterByUserLanguages($posts);
            }
            $posts = $posts->whereNotNull('published_at')
                ->whereNotNull('encrypted_id')
                ->get();
        }
        return $posts;
    }

    public static function getPostSeries($pageCount, $filterBy)
    {
        $categoryFilterIds = CategoryFilter::lists('category_id');
        $postFilterIds = PostCategory::whereIn('category_id', $categoryFilterIds)->lists('post_id');

        $postSeries = PostSeries::with('post', 'post.categories', 'post.user', 'post.user.profile', 'post.wiki', 'series', 'series.group')
            ->whereNotIn('post_id', $postFilterIds)
            ->whereNotNull('published_at');

        $postSeries = self::filterByUserLanguages($postSeries);
        $postSeries = self::filterByGroupSecretNotInUser($postSeries, Auth::user(), self::WALL_ALL);
        $postSeries = self::filterPostsBy($filterBy, $postSeries);

        $offset = $pageCount * self::PER_PAGE;
        $postSeries = $postSeries->orderBy('published_at', 'desc')
            ->take(self::PER_PAGE)
            ->skip($offset)
            ->get();
        
        return $postSeries;
    }

    public static function getPostByUserContest($contest, $userId)
    {
        $startDate = Carbon::parse($contest->term_start)->format('Y-m-d');
        $endDate = Carbon::parse($contest->term_end)->format('Y-m-d');
        $categories = $contest->categories()->lists('category_id');

        $posts = Post::where('user_id', $userId)
            ->where(DB::raw('DATE_FORMAT(published_at, "%Y-%m-%d")'), '>=', $startDate)
            ->where(DB::raw('DATE_FORMAT(published_at, "%Y-%m-%d")'), '<=', $endDate);

        if ($categories != null) {
            $postIds = PostCategory::whereIn('category_id', $categories)->lists('post_id');
            if ($postIds != null) {
                $posts->whereIn('posts.id', $postIds);
            }
        }

        return $posts;
    }
}
