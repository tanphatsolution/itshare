<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Services\NotificationService;
use App\Data\Blog\Notification;
use View;
use Response;

class NotificationsController extends BaseController
{

    /**
     * Instantiate a new NotificationsController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->viewData['title'] = 'Notifications';
    }

    /**
     * Default notification page
     * Route /notifications/index
     * @return response
     */
    public function index()
    {
        $notifications = NotificationService::getAllNotifications($this->currentUser);
        $result = [];
        $i = 0;
        foreach ($notifications as $notification) {
            if ($i == 0) {
                $result[0]['date'] = $notification->created_at;
            }
            if (date('Ymd', strtotime($notification->created_at)) == date('Ymd', strtotime($result[$i]['date']))) {
                $result[$i]['notifications'][] = $notification;
            } else {
                $i ++;
                $result[$i]['date'] = $notification->created_at;
                $result[$i]['notifications'][] = $notification;
            }
        }
        $this->viewData['notifications'] = $result;
        $this->viewData['paginate'] = $notifications;
        return View::make('notification.index', $this->viewData);
    }

    public function fetch() {
        $input = Input::all();
        $unreadNotifyCount = NotificationService::getUnreadNotifications($this->currentUser, Notification::NOTIFY_TYPE)->count();
        $unreadRequestCount = NotificationService::getUnreadNotifications($this->currentUser, Notification::REQUEST_TYPE)->count();
        $notifications = NotificationService::fetchNotifications($this->currentUser, $input);
        $notify = View::make('notification._notifications', ['notifications' => $notifications['notifications']])->render();
        $request = View::make('notification._requests', ['requests' => $notifications['requests']])->render();
        return Response::json([
            'notify' => $notify,
            'request' => $request,
            'unreadNotifyCount' => $unreadNotifyCount,
            'unreadRequestCount' => $unreadRequestCount,
        ], 200);
    }
}
