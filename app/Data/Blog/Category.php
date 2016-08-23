<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use App\Data\Faq\Question;

class Category extends BaseModel
{
    CONST CATEGORIES_HINT_LIMIT = 5;
    CONST LIMIT_CATEGORIES_PER_PAGE = 15;
    CONST ADMIN_LIMIT_CATEGORIES_PER_PAGE = 200;
    CONST LIMIT_SEARCH_ITEMS = 8;
    CONST TOP_CATEGORIES_POST_LIMIT = 15;
    CONST LIMIT_CATEGORY_FOR_HOME = 28;
    use SoftDeletes;

    protected $table = 'categories';
    protected $guarded = ['id'];
    protected $fillable = ['name', 'img', 'short_name'];

    /**
     * Define posts relations. A Category has many posts
     * @return mixed
     */
    public function posts()
    {
        return $this->belongsToMany('App\Data\Blog\Post', 'post_categories')
            ->withTimestamps()
            ->whereNotNull('posts.encrypted_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_category');
    }

    public function followers()
    {
        return $this->belongsToMany('App\Data\System\User', 'follow_categories', 'category_id', 'user_id');
    }

    public function filtered()
    {
        return $this->hasOne('App\Data\Blog\CategoryFilter');
    }

    public function addFollower($user)
    {
        if (!$this->isFollowingBy($user)) {
            $this->followers()->attach($user->id);
            $this->increment('followers_count');
        }
    }

    public function removeFollower($user)
    {
        if ($this->isFollowingBy($user)) {
            $this->decrement('followers_count');
            $this->followers()->detach($user->id);
        }
    }

    public function isFollowingBy($user)
    {
        if ($user) {
            return (FollowCategory::where('category_id', $this->id)->where('user_id', $user->id)->count() > 0);
        }
        return false;
    }

    public static function getRules($id = null)
    {
        $rules = [
            'name' => 'required|unique:categories,name,' . $id,
            'short_name' => 'required|unique:categories,short_name,' . $id,
        ];
        $rules += Image::getUploadRules();
        return $rules;
    }

    public static function getUploadRules()
    {
        $maxSize = Config::get('limitation.max_import_categories_csv_file_size');

        return [
            'file' => 'required|max:' . $maxSize,
            'extension' => 'in:csv',
        ];
    }

    public static function getUploadMessages()
    {
        $maxSize = Config::get('limitation.max_import_categories_csv_file_size');

        return [
            'file.required' => trans('messages.category.import_not_file'),
            'file.max' => trans('messages.category.import_not_size', ['size' => $maxSize]),
            'extension.in' => trans('messages.category.import_not_csv'),
        ];
    }

    public function getImage()
    {
        $config = Config::get('image');
        $image = isset($config['category']['upload_dir_320']) && $config['category']['upload_dir_320'] != null
            ? $config['category']['upload_dir_320'] . '/' . $this->shortName . '.png' : '';

        if (!file_exists($image)) {
            $image = isset($config['group_image']['post_thumbnail_default'])
                ? $config['group_image']['post_thumbnail_default'] : '';
        }

        return URL::to('/') . '/' . $image;
    }

    public static function findByShortName($shortName)
    {
        return Category::with('publishedPosts')->where('short_name', $shortName)->first();
    }

    public function publishedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_categories', 'category_id',
            'post_id')->whereNotNull('posts.published_at')->whereNotNull('posts.encrypted_id');
    }


    public function getRecentPublishedPosts($pageCount = 0)
    {
        $recentPublishedPosts = $this->publishedPosts();
        $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();
        if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $recentPublishedPosts = $recentPublishedPosts->whereIn('language_code', $currentUserLanguages);
        }
        $result = array();
        $result['hasMore'] = $recentPublishedPosts->count('posts.id') > (($pageCount + 1)
            * CategoryService::POSTS_PER_CATEGORY_DETAIL) ? true : false;
        $result['posts'] = $recentPublishedPosts->with('categories', 'user.avatar', 'user.profile')
            ->orderBy('posts.published_at', 'desc')
            ->limit(CategoryService::POSTS_PER_CATEGORY_DETAIL)
            ->offset($pageCount * CategoryService::POSTS_PER_CATEGORY_DETAIL);
        return $result;
    }

    public function getRecentStockedPosts($pageCount = 0)
    {

        $postsInCategory = !empty($this->publishedPosts()) ? $this->publishedPosts()->get()->lists('id') : [];

        $recentStockedPosts = \App\Data\Blog\Post::with('categories', 'user.avatar', 'user.profile')
            ->whereIn('id', $postsInCategory)
            ->orderBy('posts.stocks_count', 'desc');
        $result = array();

        $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();

        if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $recentStockedPosts = $recentStockedPosts->whereIn('language_code', $currentUserLanguages);
        }

        $totalRecords = $recentStockedPosts->count();

        $result['posts'] = $recentStockedPosts
            ->limit(CategoryService::POSTS_PER_CATEGORY_DETAIL)
            ->offset($pageCount * CategoryService::POSTS_PER_CATEGORY_DETAIL);

        $result['hasMore'] = $totalRecords > (($pageCount + 1) * CategoryService::POSTS_PER_CATEGORY_DETAIL)
            ? true : false;
        return $result;
    }

    public function getStokedUserRanking()
    {
        $postCategoryTableName = PostCategory::getTableName();
        $postTableName = Post::getTableName();

        $stockedUserRanking = DB::table($postCategoryTableName)
            ->join($postTableName, $postCategoryTableName . '.post_id', '=', $postTableName . '.id')
            ->select(DB::raw($postTableName . '.user_id, ' . 'sum(' . $postTableName
                . '.stocks_count) as stocks_count'))
            ->where($postCategoryTableName . '.category_id', $this->id)
            ->whereNotNull($postTableName . '.encrypted_id')
            ->groupBy($postTableName . ' . user_id')
            ->orderBy('stocks_count', 'desc');
        return $stockedUserRanking;
    }

    public static function getTopRecentCategories($user)
    {
        $recentCategories = DB::table('categories')
            ->select(DB::raw('count(post_categories.id) as postsCount'), 'categories.id', 'name')
            ->leftJoin('post_categories', 'post_categories.category_id', '=', 'categories.id')
            ->leftJoin('posts', 'posts.id', '=', 'post_categories.post_id')
            ->where('posts.user_id', $user->id)
            ->whereNull('categories.deleted_at')
            ->whereNotNull('posts.encrypted_id')
            ->groupBy('categories.id')
            ->orderBy('postsCount', 'desc')
            ->take(self::CATEGORIES_HINT_LIMIT)
            ->get();
        return $recentCategories;
    }

    public static function getCategoriesByPostId($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return [];
        }
        return $post->categories;
    }

    public static function searchByName($name)
    {
        return Category::withTrashed()
            ->where('name', 'LIKE', ' % ' . $name . ' % ')
            ->orWhere('short_name', 'LIKE', ' % ' . $name . ' % ')
            ->paginate(self::LIMIT_SEARCH_ITEMS);
    }

    public function getTopPostsInCategory($pageCount = 0)
    {
        $postsInCategory = !empty($this->publishedPosts()) ?
            $this->publishedPosts()
                ->get()
                ->take(Config::get('limitation.posts_limit_for_ranking'))
                ->lists('id') : array();

        $postsRanking = Post::select('id', DB::raw('views_count / POW(UNIX_TIMESTAMP(NOW()) -
        UNIX_TIMESTAMP(published_at), ' . Post::AGE_GRAVITY_FOR_RANKING . ') as rank_order'))
            ->whereIn('id', $postsInCategory)
            ->whereNotNull('encrypted_id')
            ->whereNull('deleted_at')
            ->orderBy('rank_order', 'desc');

        $posts = Post::with('categories', 'user.avatar', 'user.profile')
            ->whereIn('id', $postsRanking->lists('id'))
            ->orderBy('posts.views_count', 'desc');

        $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();

        if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $posts = $posts->whereIn('language_code', $currentUserLanguages);
        }

        $result = array();
        $result['hasMore'] = $posts->count('posts.id') > (($pageCount + 1)
            * CategoryService::POSTS_PER_CATEGORY_DETAIL) ? true : false;
        $result['posts'] = $posts->limit(CategoryService::POSTS_PER_CATEGORY_DETAIL)
            ->offset($pageCount * CategoryService::POSTS_PER_CATEGORY_DETAIL);
        return $result;
    }

    public function getHelpfulPostsInCategory($pageCount = 0)
    {
        $postsInCategory = !empty($this->publishedPosts()) ?
            $this->publishedPosts()
                ->get()
                ->take(Config::get('limitation.posts_limit_for_ranking'))
                ->lists('id') : array();

        $postsRanking = Post::select('id', DB::raw('(SELECT COUNT(*) 
             FROM post_helpfuls WHERE post_helpfuls.post_id = posts.id) as helpful_order'))
            ->whereIn('id', $postsInCategory)
            ->whereNotNull('encrypted_id')
            ->whereNull('deleted_at')
            ->orderBy('helpful_order', 'desc');

        $posts = Post::with('categories', 'user.avatar', 'user.profile')
            ->whereIn('id', $postsRanking->lists('id'));

        $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();
        if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $posts = $posts->whereIn('language_code', $currentUserLanguages);
        }
        $result = array();
        $result['hasMore'] = $posts->count('posts.id') > (($pageCount + 1)
            * CategoryService::POSTS_PER_CATEGORY_DETAIL)
            ? true : false;
        $result['posts'] = $posts->limit(CategoryService::POSTS_PER_CATEGORY_DETAIL)
            ->offset($pageCount * CategoryService::POSTS_PER_CATEGORY_DETAIL);
        return $result;
    }

    public static function filterLanguageInCategory($limit = Category::TOP_CATEGORIES_POST_LIMIT, $pageCount = 0, $shortName = null)
    {
        $categories = Category::select(DB::raw('count(categories.id) as categories_count'), 'categories.name',
            'categories.short_name', 'categories.id', 'categories.posts_count', 'categories.followers_count')
            ->join('post_categories', 'post_categories.category_id', ' = ', 'categories.id')
            ->join('posts', 'posts.id', ' = ', 'post_categories.post_id')
            ->whereNotNull('posts.published_at')
            ->whereNotNull('posts.encrypted_id')
            ->whereNull('posts.deleted_at');

        $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();

        if ($currentUserLanguages != null && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $categories->whereIn('posts.language_code', $currentUserLanguages);
        }

        $categories->groupBy('categories.id')
            ->orderBy('categories_count', 'desc')
            ->take($limit);
        if (!empty($shortName)) {
            $categories->where('short_name', $shortName);
        }
        if ($pageCount > 0) {
            $categories->skip($pageCount * $limit);
        }
        return $categories;
    }
}
