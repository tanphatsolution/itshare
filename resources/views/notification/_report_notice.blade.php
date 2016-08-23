{{ trans('messages.notification.has_a_report', [
    'username' => user_img_tag($notice->sender, 40) . bold($notice->sender->username),
    'post' => bold(HTML::entities(str_limit($post->title, $limit = 30, $end = '...'))),
    'type' => $type,
]) }}
