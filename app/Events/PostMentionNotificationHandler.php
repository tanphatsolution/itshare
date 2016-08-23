<?php namespace App\Events;

use App\Data\Blog\Notification;
use App\Data\Blog\Post;
use App\Services\NotificationService;
use App\Services\RedisService;
use View;

class PostMentionNotificationHandler
{
    const EVENT_NAME = 'posts.mention';
    const ACTIVITY = 'mention';
    use MentionNotificationTrait;
        
    public function handle(Post $post)
    {
        $users = $this->getMentionedUsers($post->content);
        
        if (empty($users)) {
            return;
        }

        $input = [
            'sender_id' => $post->user_id,
            'type' => Notification::TYPE_MENTION,
            'post_id' => $post->id,
            'comment_id' => 0,
        ];


        foreach ($users as $user) {
            if ($user->id == $post->user_id || !$user->setting->receive_mention_notification) {
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
                    'url' => url_to_post($post, ['ref' => 'notification', 'notif_id' => $result->id]),
                    'view' => View::make('notification._post_mention', ['post' => $post])->render(),
                    'notifications_count' => NotificationService::updateNotificationsCount($user),
                ];
                $redis = new RedisService(json_encode($content));
                $redis->publish($user->id);
            }
        }
    }
}