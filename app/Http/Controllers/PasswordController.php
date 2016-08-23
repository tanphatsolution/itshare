<?php namespace App\Http\Controllers;

use App\Data\Blog\PasswordReminder;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use App\Events\LoginSuccessHandler;
use Input;
use Validator;
use Redirect;
use Session;
use View;
use Auth;
use Event;

class PasswordController extends BaseController
{
    use ResetsPasswords;

    /**
     * Display the password reminder view.
     *
     * @return string
     */
    public function getRemind()
    {
        $this->viewData['title'] = trans('labels.forgot_your_password');
        return View::make('password.remind', $this->viewData);
    }

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return redirect
     */
    public function postRemind()
    {
        $input = Input::only('email');
        $validator = Validator::make($input, PasswordReminder::$remindRule);
        $validator->setAttributeNames(['email' => trans('labels.email')]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($validator);
        }
        Password::sendResetLink($input, function ($message) {
            $message->subject(trans('messages.reset_password.mail_subject'));
        });
        return Redirect::back()->with('message', trans('messages.reset_password.sent_remind'));
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string $token
     * @return Redirect | string
     */
    public function getReset($token = null)
    {
        $this->viewData['title'] = trans('messages.reset_password.title_page');

        if (is_null($token) && !Session::has('message')) {
            Redirect::action('PasswordController@getRemind');
        }

        return View::make('password.reset', $this->viewData)->with('token', $token);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Redirect
     */
    public function postReset()
    {
        $input = Input::only('email', 'password', 'password_confirmation', 'token');
        $validator = Validator::make($input, PasswordReminder::$resetRule);
        $validator->setAttributeNames([
            'email' => trans('labels.email'),
            'password' => trans('labels.password_2'),
            'password_confirmation' => trans('labels.confirm_pwd'),
        ]);
        
        if ($validator->fails()) {
            return Redirect::action('PasswordController@getReset', [$input['token']])
                ->withInput($input)
                ->withErrors($validator);
        }
        $response = Password::reset($input, function ($user, $password) {
            $user->setPasswordAttribute($password);
            $user->save();
        });
        return $this->_responseResetPassword($response, $input);
    }

    protected function _responseResetPassword($response, $params)
    {
        switch ($response) {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::action('PasswordController@getReset', [$params['token']])
                    ->with('message_error', Lang::get($response));
            case Password::PASSWORD_RESET:
                if (Auth::attempt(['email' => $params['email'], 'password' => $params['password']])) {
                    Event::fire(LoginSuccessHandler::EVENT_NAME, Auth::user());
                    $this->isAdmin();
                    return Redirect::action('PasswordController@getReset', ['status' => 'done'])
                        ->with('message', trans('messages.reset_password.sent'));
                }
        }
    }
}
