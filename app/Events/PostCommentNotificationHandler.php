<?php
namespace App\Events;

use App\Data\Blog\Comment;
use App\Data\Blog\Notification;
use App\Services\NotificationService;
use App\Services\RedisService;
use View;

class PostCommentNotificationHandler
{
    const EVENT_NAME = 'posts.comment';
    const ACTIVITY = 'comment';

    public function handle(Comment $comment)
    {
        if ($comment->post->user_id === $comment->user_id
            || !$comment->post->user->setting->receive_comment_notification) {
            return;
        }
        $input = [
            'sender_id' => $comment->user_id,
            'type' => Notification::TYPE_COMMENT,
            'recipient_id' => $comment->post->user_id,
            'post_id' => $comment->post_id,
            'comment_id' => $comment->id,
            'content' => $comment->content,
        ];
        $result = Notification::createOrUpdate($input);
        if ($result !== null) {
            $content = [
                'url' => url_to_post($comment->post, ['ref' => 'notification', 'notif_id' => $result]),
                'view' => View::make('notification._post_comment', ['comment' => $comment])->render(),
                'notifications_count' => NotificationService::updateNotificationsCount($comment->post->user),
            ];
            $redis = new RedisService(json_encode($content));
            $redis->publish($comment->post->user_id);
        }
    }
}