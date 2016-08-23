<?php
namespace App\Events;

use App\Data\Blog\Notification;
use App\Data\Blog\Post;
use App\Services\NotificationService;
use App\Services\RedisService;
use View;

class FollowingUserPostNotificationHandler
{
    const EVENT_NAME = 'followingUser.post';
    const ACTIVITY = 'followingUserPost';

    public function handle($post)
    {
        if (!$post->isPublished()) {
            return;
        }

        $senderId = $post->user_id;
        $postId = $post->id;
        $sender = $post->user;
        $followers = $sender->followers;

        foreach ($followers as $user) {

            $recipient = $user->follower;
            
            if (isset($recipient->id) && $recipient->id != null) {
                $input = [
                    'sender_id' => $senderId,
                    'post_id' => $postId,
                    'type' => Notification::TYPE_FOLLOWING_POST,
                    'recipient_id' => $recipient->id,
                    'content' => '',
                ];


                $notice = Notification::where('sender_id', $senderId)
                                    ->where('post_id', $postId)
                                    ->where('type', Notification::TYPE_FOLLOWING_POST)
                                    ->where('recipient_id', $recipient->id)
                                    ->first();

                if ($notice) {
                    $notice->update(['status' => Notification::STATUS_UNREAD]);
                } else {
                    $notice = Notification::create($input);
                }

                $content = [
                    'url' => url_to_post($post),
                    'view' => View::make('notification._following_user_post',
                                [
                                    'notice' => $notice,
                                    'post' => $post,
                                ])->render(),
                    'notifications_count' => NotificationService::updateNotificationsCount($recipient),
                ];

                $redis = new RedisService(json_encode($content));
                $redis->publish($recipient->id);
            }
        }
    }
}
