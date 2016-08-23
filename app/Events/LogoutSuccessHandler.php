<?php namespace App\Events;

use Carbon\Carbon;
use App\Data\System\User;
use App\Data\Blog\Activity;

class LogoutSuccessHandler
{
    CONST EVENT_NAME = 'logout.success';

    public function handle(User $user)
    {
        $message = $user->username . trans('logs.signed') . Carbon::now();
        $activity = array(
                'message' => $message,
                'trackable_type' => Activity::ACTIVITY_LOGOUT_SUCCESS,
                'user_id' => $user->id
            );
        Activity::create($activity);
    }
}
 