<?php namespace App\Http\Controllers;

use App\Data\System\User;
use App\Facades\Authority;
use App\Services\UserService;
use Auth;
use View;
use Response;
use Input;
use DB;
use Validator;
use Exception;
use File;

class AccountController extends BaseController
{

    const CSV_FULLNAME = 'FULLNAME';
    const CSV_EMAIL = 'EMAIL';

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        if (Auth::check() && Authority::hasRole('admin')) {
            return View::make('account.create_account', $this->viewData);
        }
        return Response::view('errors.404', $this->viewData, 404);
    }

    public function postCreate()
    {
        $email = $username = '';

        if (Auth::check() && Authority::hasRole('admin')) {
            $inputFile = Input::file('csv_file');
            if (empty($inputFile)) {
                return View::make('account.create_account', $this->viewData);
            }

            $file = fopen($inputFile->getRealPath(), 'r');
            $input = Input::all();
            $sendMail = isset($input['send_mail']) ? true : false;

            DB::beginTransaction();

            while (!feof($file))
            {
                try {
                    $readFile = fgetcsv($file);
                    $fullName = $readFile[0];
                    $email = $readFile[1];

                    $data = [
                        'email' => $email
                    ];
                    $validator = Validator::make($data, User::$validateEmail);
                    if ($validator->fails() || empty($fullName)) {
                        continue;
                    }

                    $arrEmail = explode('@', $email);
                    $username = !empty($arrEmail[0]) ? $arrEmail[0] : '';
                    if (empty($username)) continue;

                    $password = str_random(8);
                    $extraParams = [
                        'username' => $username,
                        'password' => $password,
                    ];
                    $input = [
                        'name' => $fullName,
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'password_confirmation' => $password
                    ];

                    UserService::signup($input, true, $sendMail, $extraParams);
                    $this->viewData['messages'][] = [
                        'name' => $fullName,
                        'email' => $email,
                        'username' => $username,
                    ];
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    if (isset($e->errorInfo[1])) {
                        $readFile = fgetcsv($file);
                        $email = $readFile[1];
                        $username = !empty($arrEmail[0]) ? $arrEmail[0] : '';
                        $message = date('Y-m-d H:i:s') . ' : ' . 'Email : ' . $email . ' --- Username : ' . $username . ' - ';
                        File::append('file_log.txt', $message . $e->errorInfo[1] . '\n');
                        $this->viewData['messageErrors'][] = trans('messages.user.user_not_exists', ['username' => $username, 'email' => $email]);
                    }
                }
            }

            fclose($file);

            return View::make('account.create_account', $this->viewData);
        }
        return Response::view('errors.404', $this->viewData, 404);
    }

}
