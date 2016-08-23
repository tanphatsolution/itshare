<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Redirect;
use View;
use DB;
use Exception;
use App\Data\Blog\SuggestSkill;
use App\Data\Blog\Profile;
use App\Data\Blog\Setting;
use App\Data\System\User;
use App\Services\ProfileService;
use App\Services\UserService;


class ProfilesController extends BaseController
{

    /**
     * Instantiate a new ProfilesController instance.
     */
    public function __construct()
    {
        parent::__construct();
        // Authentication filter
        $this->middleware('auth', [
            'only' => [
                'getUpdate',
                'postUpdate',
            ],
        ]);
    }

    /**
     * Default profile page
     * Route /profile/index
     * @return response
     */
    public function getIndex()
    {
        return \Redirect::action('ProfilesController@getUpdate');
    }

    /**
     * Update profile page
     * Route /profile/update
     * @return response
     */
    public function getUpdate()
    {
        $this->viewData['title'] = trans('titles.public_profile');
        $this->viewData['profile'] = $this->currentUser->profile()->first();
        $this->viewData['skills'] = $this->currentUser->skills()->get();
        $this->viewData['setting'] = $this->currentUser->setting()->first();
        $this->viewData['suggestSkills'] = SuggestSkill::all()->lists('name');
        $this->viewData['currentEmail'] = $this->currentUser->email;
        $this->viewData['currentWorkEmail'] = $this->currentUser->work_email;

        $cityCountryDesc = $this->currentUser->profile()->first()->city_country_description;
        $cityCountryId = $this->currentUser->profile()->first()->cities_country_id;
        if (!is_null($cityCountryDesc)) {
            $this->viewData['cityCountry'] = [
                $cityCountryId => $cityCountryDesc,
            ];
            $this->viewData['placeId'] = $cityCountryId;
        }
        return View::make('profile.update', $this->viewData);
    }

    /**
     * Update profile information
     * Route /profile/update
     * @return \redirect
     */
    public function postUpdate(Request $request)
    {
        $input = $request->all();
        $validatorProfile = \Validator::make($input['profile'], Profile::$updateRules);
        if ($validatorProfile->fails()) {
            return \Redirect::back()
                ->withInput($input)
                ->withErrors($validatorProfile);
        }

        $updateRules = User::$updateRules;
        if ($this->currentUser->id) {
            $updateRules = [];
            foreach (User::$updateRules as $key => $rule) {
                $updateRules[$key] = str_replace(':id', $this->currentUser->id, $rule);
            }
        }

        $validatorUser = \Validator::make($input['user'], $updateRules);
        if ($validatorUser->fails()) {
            return \Redirect::back()
                ->withInput($input)
                ->withErrors($validatorUser);
        }

        $validatorWorkEmail = UserService::validatorWorkEmail($input['user'], $this->currentUser);
        if (!empty($validatorWorkEmail)) {
            return \Redirect::back()
                ->withInput($input)
                ->withErrors($validatorWorkEmail);
        }

        DB::beginTransaction();
        try {
            $profile = Profile::where('user_id', $this->currentUser->id)->first();
            $setting = Setting::where('user_id', $this->currentUser->id)->first();

            $currentEmail = $this->currentUser->email;
            $currentWorkEmail = $this->currentUser->work_email;
            $newEmail = $input['user']['email'];
            $newWorkEmail = $input['user']['work_email'];

            if (isset($input['profile']['city_country_place_id'])) {
                $input['profile']['lang'] = $this->currentUser->setting()->first()->lang;
                $cityCountryId = ProfileService::saveCitiesCountry($input['profile']);
                $input['profile']['cities_country_id'] = $cityCountryId;
            }
            $user = filter_input(INPUT_POST, 'user', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            $profile->update($input['profile']);
            $this->currentUser->update($user);
            $setting->update($input['setting']);

            $messages = trans('messages.profile.update_success');

            $flagChangePrivateEmail = false;
            if (!empty($newEmail) && ($currentEmail != $newEmail)) {
                $flagChangePrivateEmail = true;
            }

            $flagChangeWorkEmail = false;
            if (!empty($newWorkEmail) && ($currentWorkEmail != $newWorkEmail)) {
                $flagChangeWorkEmail = true;
            }

            if ($flagChangePrivateEmail) {
                if (!empty($newEmail) && ($newEmail != $currentWorkEmail)) {
                    ProfileService::sendMailConfirm($this->currentUser, $this->lang);
                    $messages .= trans('messages.profile.change_private_email');
                } else {
                    $messages .= trans('messages.profile.change_private_email_1');
                }
            }

            if ($flagChangeWorkEmail) {
                if (($flagChangePrivateEmail && ($newWorkEmail != $newEmail)) || ($newWorkEmail != $currentEmail)) {
                    ProfileService::sendMailConfirm($this->currentUser, $this->lang, true);
                    $messages .= trans('messages.profile.change_work_email');
                } else {
                    $messages .= trans('messages.profile.change_work_email_1');
                }
            }

            DB::commit();
            return \Redirect::back()->with('message', $messages);
        } catch (Exception $e) {
            DB::rollback();
            return \Redirect::back()->with('message_error', trans('messages.profile.update_fail'));
        }
    }

    public function getSuggestCities(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $result = ProfileService::getSuggestCities(urlencode($input['keyword']), $this->currentUser);

            return response()->json($result);
        }
    }
}
