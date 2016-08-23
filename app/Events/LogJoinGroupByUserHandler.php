<?php namespace App\Events;

use Carbon\Carbon;
use App\Data\Blog\Activity;

class LogJoinGroupByUserHandler
{
    CONST EVENT_NAME = 'group.joined';

    public function handle($groupUserRequest)
    {
        $message = $groupUserRequest->user->username . trans('logs.request_join_group') .
            $groupUserRequest->group->name . 'at' . Carbon::now();

        $activity = array(
            'message' => $message,
            'trackable_type' => Activity::ACTIVITY_JOINED_GROUP,
            'user_id' => $groupUserRequest->user->id,
            'group_id' => $groupUserRequest->group_id
        );
        Activity::create($activity);
    }
}
