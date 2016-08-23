<?php namespace App\Events;

use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupUser;
use App\Data\Blog\GroupSetting;
use App\Data\Blog\Notification;
use App\Data\Blog\Post;
use App\Data\System\User;
use App\Services\RedisService;
use App\Services\NotificationService;
use View;

class GroupPostNotificationHandler
{
    const EVENT_NAME = 'groups.posts';
    const ACTIVITY = 'groupPost';

    public function handle(GroupPost $groupPost)
    {
        if ($groupPost->approved != GroupPost::GROUP_POST_APPROVED) {
            return;
        }

        $postId = $groupPost->post_id;
        $post = Post::find($postId);
        $senderId = $post->user_id;
        $groupId = $groupPost->group_id;
        $group = $groupPost->group;

        $groupUserIds = GroupUser::where('group_id', $groupPost->group_id)
                            ->where('status', GroupUser::STATUS_MEMBER)
                            ->where('user_id', '<>', $senderId);
        if (isset($group->groupSetting->approve_post_flag) && $group->groupSetting->approve_post_flag == GroupSetting::POST_NEED_APPROVE) {
            $groupUserIds = $groupUserIds->where('role', GroupUser::ROLE_MEMBER);
        }
        $groupUserIds = $groupUserIds->lists('user_id');
        $groupUsers = User::whereIn('id', $groupUserIds)->get();

        foreach ($groupUsers as $groupUser) {
            $input = [
                'sender_id' => $senderId,
                'type' => Notification::TYPE_POST_IN_GROUP,
                'recipient_id' => $groupUser->id,
                'post_id' => $postId,
                'group_id' => $groupId,
                'content' => '',
            ];

            $notice = Notification::create($input);

            if ($notice) {
                $content = [
                    'url' => url_to_post($post),
                    'view' => View::make('notification._group_new_post', ['post' => $post, 'group' => $group])->render(),
                    'notifications_count' => NotificationService::updateNotificationsCount($groupUser),
                ];
                $redis = new RedisService(json_encode($content));
                $redis->publish($groupUser->id);
            }
        }
    }
}
