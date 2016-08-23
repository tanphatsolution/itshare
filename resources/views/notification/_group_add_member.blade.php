{{ trans('messages.notification.has_added_to_group', [
    'sender' => user_img_tag($notice->sender, 40) . bold($notice->sender->username),
    'group' => bold(HTML::entities(str_limit($group->name, $limit = 30, $end = '...'))),
]) }}
