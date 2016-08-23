<?php namespace App\Events;

use App\Data\Blog\Comment;
use App\Data\Blog\Notification;
use App\Services\NotificationService;
use App\Services\RedisService;
use View;

class CommentMentionNotificationHandler
{
    const EVENT_NAME = 'comments.mention';
    const ACTIVITY = 'mention';

    use MentionNotificationTrait;

    public function handle(Comment $comment)
    {
        $users = $this->getMentionedUsers($comment->content);
        if (empty($users)) {
            return;
        }

        $input = [
            'sender_id' => $comment->user_id,
            'type' => Notification::TYPE_MENTION,
            'post_id' => $comment->post_id,
            'comment_id' => $comment->id,
        ];

        foreach ($users as $user) {
            if (!$user->setting->receive_mention_notification || $user->id == $comment->user_id) {
                continue;
            }
            $input['recipient_id'] = $user->id;
            $notification = Notification::findByArray($input);
            if ($notification) {
                continue;
            }
            $result = Notification::create($input);
            if ($result) {
                $content = [
                    'url' => url_to_post($comment->post, ['ref' => 'notification', 'notif_id' => $result->id]),
                    'view' => View::make('notification._comment_mention', ['comment' => $comment])->render(),
                    'notifications_count' => NotificationService::updateNotificationsCount($user),
                ];
                $redis = new RedisService(json_encode($content));
                $redis->publish($user->id);
            }
        }
    }
}