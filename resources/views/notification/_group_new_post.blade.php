{{ trans('messages.notification.has_posted_in_group', [
    'username' => user_img_tag($post->user, 40) . bold($post->user->username),
    'group' => bold(HTML::entities(str_limit($group->name, $limit = 30, $end = '...'))),
]) }}
