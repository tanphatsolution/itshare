<?php namespace App\Events;

use Carbon\Carbon;
use App\Http\Controllers\BaseController;
use Auth;
use App\Data\Blog\Activity;

class LogKeywordSearchByUserHandler extends BaseController
{
    const EVENT_NAME = 'search.write_log_keyword';

    public function handle($keyword = '')
    {
        if (!ctype_space($keyword)) {
            $userName = Auth::check() ? $this->currentUser->username : trans('logs.someone');
            $userId = Auth::check() ? $this->currentUser->id : 0;

            $message = $userName . trans('logs.have_searched') . $keyword . trans('logs.on_viblo') . Carbon::now();
            $activity = array(
                'message' => $message,
                'trackable_type' => Activity::ACTIVITY_KEYWORD_SEARCH,
                'user_id' => $userId
            );
            Activity::create($activity);
        }
    }
}
