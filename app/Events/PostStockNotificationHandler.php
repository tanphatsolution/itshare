<?php namespace App\Events;

use App\Data\Blog\Notification;
use App\Data\Blog\Stock;
use App\Services\NotificationService;
use App\Services\RedisService;
use View;

class PostStockNotificationHandler
{
    const EVENT_NAME = 'posts.stock';
    const ACTIVITY = 'stock';

    public function handle(Stock $stock)
    {
        if ($stock->post->user_id === $stock->user_id
            || !$stock->post->user->setting->receive_stock_notification) {
            return;
        }
        $input = [
            'sender_id' => $stock->user_id,
            'type' => Notification::TYPE_STOCK,
            'recipient_id' => $stock->post->user_id,
            'post_id' => $stock->post_id,
            'content' => '',
        ];
        $result = Notification::createOrUpdate($input);
        if ($result !== null) {
            $content = [
                'url' => url_to_post($stock->post, ['ref' => 'notification', 'notif_id' => $result]),
                'view' => View::make('notification._post_stock', ['stock' => $stock])->render(),
                'notifications_count' => NotificationService::updateNotificationsCount($stock->post->user),
            ];
            $redis = new RedisService(json_encode($content));
            $redis->publish($stock->post->user_id);
        }
    }
}