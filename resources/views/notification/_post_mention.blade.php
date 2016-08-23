{{ trans('messages.notification.has_mentioned_in_a_post', [
    'username' => isset($stock->user) ? user_img_tag($stock->user, 40) . bold($post->user->username) : bold($post->user->username),
    'post' => bold(HTML::entities(str_limit($post->title, $limit = 30, $end = '...'))),
]) }}
