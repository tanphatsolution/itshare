<?php namespace App\Http\Controllers;

use App\Data\System\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use File;
use View;
use Redirect;

class SetTimeController extends BaseController
{
    private $_fileName = 'magazine_config.log';

    public function getForm()
    {
        $this->viewData['serverTime'] = Carbon::now();
        $this->viewData['weeklyDate'] = '';
        $this->viewData['weeklyTimeStart'] = '';
        $this->viewData['weeklyTimeEnd'] = '';
        $this->viewData['monthlySendDate'] = '';
        $this->viewData['monthlySendTime'] = '';
        $this->viewData['noPostSendDate'] = '';
        $this->viewData['noPostSendTime'] = '';
        $this->viewData['dateOfWeekIndex'] = '';
        $this->viewData['noOfWeek'] = '';

        if(File::exists(storage_path(). '/logs/'. $this->_fileName)) {
            $info = json_decode(File::get(storage_path(). '/logs/'. $this->_fileName), true);
            $this->viewData['weeklyDate'] = $info['weeklyDate'];
            $this->viewData['weeklyTimeStart'] = $info['weeklyTimeStart'];
            $this->viewData['weeklyTimeEnd'] = $info['weeklyTimeEnd'];
            $this->viewData['monthlySendDate'] = $info['monthlySendDate'];
            $this->viewData['monthlySendTime'] = $info['monthlySendTime'];
            $this->viewData['noPostSendDate'] = $info['noPostSendDate'];
            $this->viewData['noPostSendTime'] = $info['noPostSendTime'];
            $this->viewData['dateOfWeekIndex'] = $info['dateOfWeekIndex'];
            $this->viewData['noOfWeek'] = $info['noOfWeek'];
        }

        return View::make('settime.view', $this->viewData);
    }

    public function postForm()
    {
        if (File::exists(storage_path() . '/logs/' . $this->_fileName)) {
            File::delete(storage_path() . '/logs/' . $this->_fileName);
        }
        File::put(storage_path() . '/logs/' . $this->_fileName, json_encode(Input::all()));
        chmod(storage_path() . '/logs/' . $this->_fileName, 0777);

        return Redirect::to('/settime');
    }

    public function getUser()
    {
        $input = Input::all();
        echo '<a href="'. url('/settime') .'" >Back</a>', '<pre>';
        if (!empty($input['email'])) {
            $user = User::where(function($query) use ($input) {
                $query->where('users.email', $input['email'])
                    ->orWhere('users.work_email', $input['email']);
            })->get();
            print_r($user->toArray());
        }
        echo '</pre>';
    }

    public function postUser()
    {
        $input = Input::all();
        $user = User::where('username', $input['username'])->first();
        if (isset($user)) {
            $user->email = $input['email'];
            $user->work_email = $input['work_email'];
            $user->save();
        }

        return Redirect::to('/settime');
    }

    public function clearOffset()
    {
        if(File::exists(storage_path(). '/logs/offset.log')) {
            File::delete(storage_path(). '/logs/offset.log');
        }

        return Redirect::to('/settime');
    }

    public function sendEmail()
    {
        if(File::exists(storage_path(). '/logs/offset.log')) {
            File::delete(storage_path(). '/logs/offset.log');
        }

        NotificationService::sendMailMagazineWeekly();
        NotificationService::sendMailMagazineMonthly();
        NotificationService::sendMailMagazineMonthlyNoPost();

        return Redirect::to('/settime');
    }

    public function readLog()
    {
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=\'laravel.log\'');
        header('Content-Type: application/zip');
        header('Content-Transfer-Encoding: binary');
        readfile(storage_path(). '/logs/laravel.log');
    }
}
