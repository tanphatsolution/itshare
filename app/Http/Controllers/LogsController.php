<?php namespace App\Http\Controllers;

use App\Data\Blog\Activity;
use Carbon\Carbon;
use Request;
use Input;
use Auth;

class LogsController extends BaseController 
{
    /**
     * Write logs to activities table
     *
     * @return Response
     */
    public function store()
    {
        if (Request::ajax()) {
            $params = Input::all();

            if (isset($params['action_code']) && $params['action_code']) {
                $userName = Auth::check() ? $this->currentUser->username : trans('logs.someone');
                $userId = Auth::check() ? $this->currentUser->id : 0;
                $position = isset($params['position']) && $params['position'] ? $params['position'] : 0;
                $message = $userName . trans('logs.connect_to') . $params['action_code'] .' from '. $params['screen_code'] .' at '. Carbon::now();
                
                $activity = array(
                    'message' => $message,
                    'user_id' => $userId,
                    'screen_code' => $params['screen_code'],
                    'action_code' => $params['action_code'],
                    'position_click' => $position,
                );
                Activity::create($activity);
            }
        }
    }
}
