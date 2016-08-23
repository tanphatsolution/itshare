<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use Response;
use DB;
use App;
use Exception;
use Carbon\Carbon;
use Cache;
use Config;
use App\Services\LanguageService;
use App\Data\Blog\UserPostLanguage;
use App\Data\Blog\Category;

class UserPostLanguagesController extends BaseController
{
    /**
     * Instantiate a new UserPostLanguagesController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth', [
            'only' => [
                'getPostLanguages',
                'postLanguages'
            ]
        ]);

        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => [
                'getStatistic',
            ]
        ]);
    }

    public function getPostLanguages()
    {
        $this->viewData['title'] = trans('titles.language');
        $this->viewData['setting'] = $this->currentUser->setting;
        $this->viewData['languages'] = UserPostLanguage::getCurrentUserLanguages();
        return View::make('setting.language', $this->viewData);
    }

    public function postLanguages(Request $request)
    {
        $input = $request->all();

        if (Auth::check()) {

            if (isset($input['allLanguages']) || (isset($input['languages']) && !is_null($input['languages']))) {
                $oldSettingLanguages = UserPostLanguage::where('user_id', $this->currentUser->id);
                $oldSettingLanguages->delete();
            }

            if (isset($input['allLanguages']) || empty($input['languages']) && (!isset($input['changeSysLang']) &&
                    !isset($input['changeDefaultPostLang']) && !isset($input['changeLanguageAllPosts']))
            ) {
                if (isset($input['languages']) && $input['languages'] != null) {
                    UserPostLanguage::create([
                        'user_id' => $this->currentUser->id,
                        'language_code' => UserPostLanguage::SETTING_ALL_LANGUAGES,
                    ]);
                } else {
                    $oldSettingLanguages = UserPostLanguage::where('user_id', $this->currentUser->id);
                    $oldSettingLanguages->delete();
                    UserPostLanguage::create([
                        'user_id' => $this->currentUser->id,
                        'language_code' => UserPostLanguage::SETTING_ALL_LANGUAGES,
                    ]);
                }
            } elseif (isset($input['languages']) && !is_null($input['languages'])) {
                $languages = array_unique($input['languages']);
                foreach ($languages as $language) {
                    UserPostLanguage::create([
                        'user_id' => $this->currentUser->id,
                        'language_code' => $language,
                    ]);
                }
            }

            Cache::forget(Config::get('app.app_name', 'viblo') . '_post_Categories');

            if (isset($input['changeDefaultPostLang']) || isset($input['changeSysLang'])) {
                $this->currentUser->setting()->first()->update($input);
                if (isset($input['lang']) && $input['lang'] != null) {
                    App::setLocale($input['lang']);
                }
            } elseif (isset($input['changeLanguageAllPosts'])) {
                $this->currentUser->setting()->first()->update($input);
                $this->currentUser->publishedPosts()->update(['language_code' => $input['default_post_language']]);
            }
        }

        return redirect()->action('UserPostLanguagesController@getPostLanguages')
            ->with('message', trans('messages.setting.language_update_success'));
    }

    public function settingLanguage(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $request->all();
                $languages = UserPostLanguage::getCurrentUserLanguages();
                $setting = $this->currentUser->setting()->first();
                $modal = View::make('modals.language_settings', ['languages' => $languages, 'setting' => $setting])->render();
            } else {
                $modal = View::make('modals.language_settings_not_auth', ['languages' => LanguageService::getPostFilterLanguage()])->render();
            }

            return Response::json([
                'modal' => $modal
            ], 200);
        }
    }

    public function settingFilterPostLanguages(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $error = false;
            $html = '';
            if (Auth::check()) {
                Cache::forget('user_' . $this->currentUser->id . '_post_language');
                DB::beginTransaction();
                try {
                    $oldSettingLanguages = UserPostLanguage::where('user_id', $this->currentUser->id);
                    $oldSettingLanguages->delete();
                    if (($input['type'] == 'allLanguages') || !isset($input['languages'])) {
                        UserPostLanguage::create([
                            'user_id' => $this->currentUser->id,
                            'language_code' => UserPostLanguage::SETTING_ALL_LANGUAGES,
                        ]);
                    } elseif (isset($input['languages'])) {
                        $languages = array_unique($input['languages']);
                        foreach ($languages as $language) {
                            UserPostLanguage::create([
                                'user_id' => $this->currentUser->id,
                                'language_code' => $language,
                            ]);
                        }
                    }
                    DB::commit();
                    $languages = UserPostLanguage::getCurrentUserLanguages();
                    Cache::forget(Config::get('app.app_name', 'viblo') . '_post_Categories');
                    $html = View::make('setting._list_languages', ['languages' => $languages])->render();
                } catch (Exception $e) {
                    DB::rollback();
                    $error = true;
                }

            } else {
                if (($input['type'] == 'allLanguages') || !isset($input['languages'])) {
                    $languages = [UserPostLanguage::SETTING_ALL_LANGUAGES];
                } elseif (isset($input['languages'])) {
                    $languages = array_unique($input['languages']);
                } else {
                    $languages = 'vi';
                }
                LanguageService::setPostFilterLanguage($languages);
                Cache::forget(Config::get('app.app_name', 'viblo') . '_post_Categories');
                $html = View::make('setting._list_languages', ['languages' => $languages])->render();
            }
            return Response::json([
                'error' => $error,
                'html' => $html,
            ], 200);
        }
    }

    public function settingLanguageSystem(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $error = false;
            if (Auth::check()) {
                try {
                    $this->currentUser->setting()->first()->update($input);
                } catch (Exception $e) {
                    $error = true;
                }
            } else {
                LanguageService::setSystemLang($input['lang']);
            }

            return Response::json([
                'error' => $error,
            ], 200);
        }
    }

    public function settingDefaultPostLang(Request $request)
    {
        if ($request->ajax()) {

            $input = $request->all();
            $error = false;
            DB::beginTransaction();

            try {
                $this->currentUser
                    ->setting()->first()
                    ->update([
                        'default_post_language' => $input['lang']
                    ]);

                if ($input['type'] == 'defaultAll') {
                    $this->currentUser
                        ->publishedPosts()
                        ->update([
                            'language_code' => $input['lang']
                        ]);
                }

                DB::commit();

            } catch (Exception $e) {

                DB::rollback();
                $error = true;
            }

            return Response::json([
                'error' => $error,
            ], 200);
        }
    }

    public function settingTopPageLang(Request $request)
    {
        if ($request->ajax()) {

            $input = $request->all();
            $error = false;

            if (Auth::check()) {

                try {
                    $this->currentUser
                        ->setting()
                        ->first()
                        ->update([
                            'top_page_language' => $input['lang']
                        ]);

                } catch (Exception $e) {
                    $error = true;
                }

            } else {
                LanguageService::setTopPageLang($input['lang']);
            }

            return Response::json([
                'error' => $error,
            ], 200);
        }
    }

    public function getStatistic(Request $request)
    {
        $input = $request->all();
        $type = LanguageService::POST_FILTER_LANG;
        if (isset($input['filter'])) {
            $type = $input['filter'];
        }

        $languages = LanguageService::getLangStatistic($type);
        $filterLangOption = LanguageService::getFilterLangOption();

        $this->viewData['title'] = trans('titles.language_management');
        $this->viewData['filter'] = $type;
        $this->viewData['languages'] = $languages;
        $this->viewData['filterLangOption'] = $filterLangOption;

        return View::make('languages.statistic', $this->viewData);
    }
}
