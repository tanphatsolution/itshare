<?php namespace App\Http\Controllers;

use App\Data\Blog\Category;
use App\Data\Blog\Group;
use App\Data\Blog\Post;
use App\Data\Blog\PostCategory;
use App\Data\Blog\UserPostLanguage;
use App\Data\System\User;
use App\Facades\Authority;
use App\Services\GroupService;
use App\Services\PostService;
use File;
use URL;
use Response;
use Redirect;

class RssController extends BaseController
{
    const TOP_POST = 'top';
    const LIMIT_WORD_DESC_POST = 1000;
    const FOLDER_RSS = '/rss/';
    const DESC_TYPE_ORDER = 'DESC';
    const TOPCLIP_RSS_FILE_NAME = 'topclips.rss';

    public function __construct()
    {
        parent::__construct();
        if (!File::isDirectory(public_path(). self::FOLDER_RSS)) {
            File::makeDirectory(public_path(). self::FOLDER_RSS, 0777, true, true);
        }
    }

    /**
     * Create and redirect to file RSSfeed
     * @param none
     * @return void
     */
    public function getRssAllPosts($language)
    {
        if (!Authority::check()) {
            $posts = Post::with('user')->where('language_code', $language)->get();
            $fileName = 'postall' . $language . '.rss';
        } else {
            $userLanguages = UserPostLanguage::getCurrentUserLanguages();
            if ($userLanguages[0] == UserPostLanguage::SETTING_ALL_LANGUAGES) {
                $posts = Post::with('user')->where('user_id', $this->currentUser->id)
                                           ->orderBy('id', self::DESC_TYPE_ORDER)
                                           ->get();
            } else {
                $posts = Post::with('user')->where('user_id', $this->currentUser->id)
                                           ->whereIn('language_code', $userLanguages)
                                           ->orderBy('id', self::DESC_TYPE_ORDER)
                                           ->get();
            }
            $fileName = $this->currentUser->username . '_posts' . '.rss';
        }
        $this->createFileRssFeed($posts, $fileName);
        return Redirect::to(self::FOLDER_RSS . $fileName);
    }
    
    /**
     * Create and redirect to file RSSfeed User following
     * @param none
     * @return void
     */
    public function getRssByUserFollowing($username)
    {
        $user = User::where('username', $username)->first();
        if (!is_null($user)) {
            $userIdFollowing = $user->following->lists('id');
            $posts = Post::with('user')->whereIn('user_id', $userIdFollowing)
                                           ->orderBy('id', self::DESC_TYPE_ORDER)
                                           ->get();
            $fileName = 'following_by_'. $user->username . '_posts' . '.rss';
            $this->createFileRssFeed($posts, $fileName);
            return Redirect::to(self::FOLDER_RSS . $fileName);
        } else {
            return Response::view('errors.404', $this->viewData, 404);
        }
    }

    /**
     * Create and redirect to file RSSfeed by category
     * @param none
     * @return void
     */
    public function getRssByCategory($categoryShortName)
    {
        $category = Category::findByShortName($categoryShortName);
        $postIds = PostCategory::where('category_id', $category->id)->lists('post_id');

        $posts = Post::with('user')
                            ->whereIn('id', $postIds)
                            ->whereNotNull('encrypted_id')
                            ->whereNull('deleted_at')
                            ->orderBy('id', self::DESC_TYPE_ORDER)
                            ->get();

        $fileName = $category->encrypted_id. '_posts.rss';
        $this->createFileRssFeed($posts, $fileName);
        return Redirect::to(self::FOLDER_RSS . $fileName);
    }

    /**
     * Create and redirect to file RSSfeed by user
     * @param none
     * @return void
     */
    public function getRssByUser($username)
    {
        $user = User::where('username', $username)->first();
        if(!is_null($user)) {
            $posts = Post::with('user')
                            ->where('user_id', $user->id)
                            ->whereNotNull('published_at')
                            ->whereNotNull('encrypted_id')
                            ->orderBy('id', self::DESC_TYPE_ORDER)
                            ->get();

            $fileName = $user->username. '_posts.rss';
            $this->createFileRssFeed($posts, $fileName);
            return Redirect::to(self::FOLDER_RSS . $fileName);
        } else {
            return Response::view('errors.404', $this->viewData, 404);
        }
    }

    /**
     * Create and redirect to file RSSfeed by top clip
     * @param none
     * @return void
     */
    public function getRssByTopClip()
    {
        $posts = PostService::filterRssPosts(PostService::WALL_RECENT);
        
        $fileName = 'topclip_posts.rss';
        $this->createFileRssFeed($posts, $fileName);
        return Redirect::to(self::FOLDER_RSS . $fileName);
    }

    /**
     * Create and redirect to file RSSfeed by top post
     * @param none
     * @return void
     */
    public function getRssByTopPosts()
    {
        $posts = PostService::filterRssPosts(PostService::WALL_TOP);

        $fileName = 'top_posts.rss';
        $this->createFileRssFeed($posts, $fileName);
        return Redirect::to(self::FOLDER_RSS . $fileName);
    }

    /**
     * Create and redirect to file RSSfeed by top post
     * @param none
     * @return void
     */
    public function getRssByHelpfulPosts()
    {
        $posts = PostService::filterRssPosts(PostService::WALL_HELPFUL);
        
        $fileName = 'helpful_posts.rss';
        $this->createFileRssFeed($posts, $fileName);
        return Redirect::to(self::FOLDER_RSS . $fileName);
    }

    /**
     * Create and redirect to file RSSfeed by top post
     * @param none
     * @return void
     */
    public function getRssByNewPosts()
    {
        $posts = PostService::filterRssPosts(PostService::WALL_ALL);

        $fileName = 'new_posts.rss';
        $this->createFileRssFeed($posts, $fileName);
        return Redirect::to(self::FOLDER_RSS . $fileName);
    }

    /**
     * Create file RSSfeed
     * @param $posts
     * @return flase or true
     */
    protected function createFileRssFeed($posts, $fileName, $series = [])
    {
        $feed = \Rss::feed('2.0', 'UTF-8');
        $infoChannels = array(
            'title' => trans('rss.channel_title'),
            'description' => trans('rss.channel_description'),
            'link' => trans('rss.channel_link'),
        );
        $feed->channel($infoChannels);

        // Add series to rss
        if ($posts != null) {
            foreach($posts as $post) {
                $url = isset($post->username) && $post->username ? 
                    URL::to($post->username. '/posts/'. $post->encrypted_id) : 
                    URL::to($post->user->username. '/posts/'. $post->encrypted_id);

                $feed->item(array(
                        'title' => $post->title,
                        'description|cdata' => \Str::limit($post->content, self::LIMIT_WORD_DESC_POST),
                        'link' => $url,
                    )
                );
            }
        }

        // Add series to rss
        if ($series != null) {
            foreach ($series as $seri) {
                $feed->item(array(
                        'title' => $seri->title,
                        'description|cdata' => \Str::limit($seri->description, self::LIMIT_WORD_DESC_POST),
                        'link' => URL::to('groups/'. $seri->group_encrypted_id .'/groupseries/'. $seri->id),
                    )
                );       
            }
        }

        //Create file rss and permission
        $feed->save(public_path(). self::FOLDER_RSS . $fileName);    
        if (File::exists(public_path().  self::FOLDER_RSS .$fileName)) {
            chmod(public_path(). self::FOLDER_RSS . $fileName, 0777);
        }
    }
}
