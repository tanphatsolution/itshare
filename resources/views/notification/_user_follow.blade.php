{{ trans('messages.notification.has_followed_you', [
    'username' => user_img_tag($userRelationships->follower, 40) . bold($userRelationships->follower->username),
]) }}.
