<?php namespace App\Events;

use App\Data\Blog\PostView;
use Illuminate\Session\Store;
use App\Data\Blog\Post;
use DB;

class ViewPostHandler
{
    private $session;

    CONST TIME_DELAY_COUNTER = 3600; //default an hour
    CONST EVENT_NAME = 'posts.view';

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function handle(Post $post)
    {
        if (!$this->isPostViewed($post)) {

            $post->increment('views_count');

            $postView = PostView::where('post_id', $post->id)
                ->where(DB::raw('DATE(created_at)'), DB::raw('DATE(now())'))
                ->first();

            if (!$postView) {
                PostView::create([
                    'post_id' => $post->id,
                    'views_count' => 1,
                ]);
            } else {
                $postView->increment('views_count');
            }

            $this->storePost($post);
        }
    }

    private function isPostViewed($post)
    {
        $now = time();
        $viewed = $this->session->get('viewed_posts');
        if (!isset($viewed[$post->id])) {
            return false;
        }

        $elapsedTime = $now - $viewed[$post->id];

        if (isset($viewed[$post->id]) && $elapsedTime > self::TIME_DELAY_COUNTER) {
            return false;
        }

        return true;
    }

    private function storePost($post)
    {
        $key = 'viewed_posts.' . $post->id;
        $this->session->put($key, time());
    }
}
