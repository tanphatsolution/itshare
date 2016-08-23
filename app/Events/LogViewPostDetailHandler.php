<?php namespace App\Events;

use App\Data\Blog\Activity;
use App\Data\Blog\Post;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LogViewPostDetailHandler extends BaseController
{
    CONST EVENT_NAME = 'Post.detail';

    public function handle(Post $post)
    {
        $categories = is_null($post->categories->lists('name')) ? '' : implode(',', $post->categories->lists('name')->toArray());
        $userName = Auth::check() ? $this->currentUser->username : trans('logs.someone');
        $userId = Auth::check() ? $this->currentUser->id : 0;
        $message = $userName . trans('logs.have_viewed_post') . $post->title . trans('logs.in_category') . $categories . 'at' . Carbon::now();

        $activity = array(
            'message' => $message,
            'trackable_type' => Activity::ACTIVITY_VIEW_POST,
            'user_id' => $userId,
            'post_id' => $post->id
        );

        Activity::create($activity);
    }
}
