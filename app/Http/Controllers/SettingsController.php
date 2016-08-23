<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use View;

use App\Data\Blog\Setting;
use App\Services\SettingService;

class SettingsController extends BaseController
{

    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware('csrf', [
            'only' => [
                'postPrivacy',
            ]
        ]);
        $this->middleware("authority:'','',admin", ['only' => ['getAdminIndex']]);
    }

    /**
     * Default setting page
     * Route /settings/index
     * @return Response
     */
    public function getIndex()
    {
        $this->viewData['title'] = trans('titles.setting_page');
        return View::make('setting.index', $this->viewData);
    }

    public function getAdminIndex()
    {
        $this->viewData['title'] = trans('messages.setting.index_title');
        return View::make('setting.admin_index', $this->viewData);
    }

    public function postPrivacy(Request $request)
    {
        $inputs = $request->all();
        $inputs = SettingService::prepareInput($inputs);
        $this->currentUser->setting()->first()->update($inputs);

        return Redirect::action('SettingsController@getPrivacy')
            ->with('message', trans('messages.setting.update_success'));
    }

    public function getNotification()
    {
        $this->viewData['title'] = trans('titles.notification_setting');
        $this->viewData['notifiSetting'] = Setting::getNotificationSetting($this->currentUser);
        return View::make('setting.notification', $this->viewData);
    }

    public function postNotification(Request $request)
    {
        $inputs = $request->only(Setting::getNotificationSettingFields());
        $inputs = SettingService::prepareInputNotification($inputs);
        $this->currentUser->setting()->first()->update($inputs);
        return Redirect::action('SettingsController@getNotification')
            ->with('message', trans('messages.setting.update_notification_success'));
    }

    public function getViblo()
    {
        $this->viewData['title'] = trans('titles.service_setting');
        $this->viewData['setting'] = $this->currentUser->setting()->first();
        return View::make('setting.viblo', $this->viewData);
    }

    public function postViblo(Request $request)
    {
        $inputs = $request->all();
        $this->currentUser->setting()->first()->update($inputs);

        return Redirect::action('SettingsController@getViblo')
            ->with('message', trans('messages.setting.update_viblo_setting_success'));
    }
}
