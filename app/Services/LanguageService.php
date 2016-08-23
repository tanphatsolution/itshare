<?php namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use Auth;
use Config;
use DB;
use Exception;
use App\Libraries\DetectLanguageByIpHelper;

use App\Data\Blog\Post;
use App\Data\Blog\Setting;
use App\Data\System\User;
use App\Data\Blog\UserPostLanguage;

class LanguageService
{

    CONST COOKIE_TIME = 31536000;
    CONST POST_FILTER_LANG = 0;
    CONST SYSTEM_LANG = 1;
    CONST DEFAULT_USER_POST_LANG = 2;
    CONST PUBLISHED_POST_LANG = 3;

    public static function getDetectedCountryAndLang()
    {
        $detect = new DetectLanguageByIpHelper();
        $country = $detect->detectCountry();
        $language = $detect->detectLanguage();

        return [
            'country' => $country,
            'language' => $language,
        ];
    }

    public static function setDefaultLanguage()
    {
        $detected = [];
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $setting = $user->setting;
            $userLanguages = $user->userPostLanguages()->get(['language_code']);
            if ((isset($setting->toppage_language_setting_flag) && $setting->toppage_language_setting_flag == 0) || (isset($setting->post_language_setting_flag) && $setting->post_language_setting_flag == 0) || ($userLanguages->count() == 0)) {
                $detected = self::getDetectedCountryAndLang();
            }

            if (isset($setting->toppage_language_setting_flag) && $setting->toppage_language_setting_flag == 0) {
                self::setDefaultTopPageLangAuth($detected['country'], $setting);
            }

            if (isset($setting->post_language_setting_flag) && $setting->post_language_setting_flag == 0) {
                self::setDefaultPostLanguageAuth($detected['language'], $setting);
            }

            if ($userLanguages->count() == 0) {
                self::setDefaultPostFilterLanguageAuth($detected['language'], $userId);
            }

        } else {

            if (is_null(get_cookie('topPageLanguage')) || is_null(get_cookie('postFilterLanguage'))) {
                $detected = self::getDetectedCountryAndLang();
            }

            if (is_null(get_cookie('topPageLanguage'))) {
                $topPageLanguage = self::setDefaultTopPageLangNotAuth($detected['country']);
            }

            if (is_null(get_cookie('postFilterLanguage'))) {
                $postFilterLanguage = self::setDefaultPostFilterLangNotAuth($detected['language']);
            }

            $topPageLanguage = !isset($topPageLanguage) ? self::getTopPageLang() : $topPageLanguage;
            $postFilterLanguage = !isset($postFilterLanguage) ? self::getPostFilterLanguage() : $postFilterLanguage;

            if (!in_array($topPageLanguage, ['vi', 'en'])) {
                delete_cookie('topPageLanguage');
                $detected = self::getDetectedCountryAndLang();
                $topPageLanguage = self::setDefaultTopPageLangNotAuth($detected['country']);
            }

            return [
                'topPageLanguage' => $topPageLanguage,
                'postFilterLanguage' => $postFilterLanguage,
            ];
        }
    }

    public static function setDefaultTopPageLangAuth($detectedCountry, $setting)
    {
        if ($detectedCountry == 'VN') {
            $setting->update([
                'top_page_language' => 'vi',
                'toppage_language_setting_flag' => 1,
            ]);

        } else {

            $setting->update([
                'top_page_language' => 'en',
                'toppage_language_setting_flag' => 1,
            ]);
        }
    }

    public static function setDefaultPostLanguageAuth($detectedLanguage, $setting)
    {
        $setting->update([
            'default_post_language' => $detectedLanguage[0],
            'post_language_setting_flag' => 1,
        ]);
    }

    public static function setDefaultPostFilterLanguageAuth($languages, $userId)
    {
        DB::beginTransaction();
        $postFilterLanguage = in_array('en', $languages) ?
            $languages : array_merge(['en'], $languages);

        try {
            $oldSettingLanguages = array();
            $oldLanguages = UserPostLanguage::select('language_code')->where('user_id', $userId)->get();
            if ($oldLanguages->count() > 0) {
                foreach($oldLanguages as $item) {
                    $oldSettingLanguages[] = $item->language_code;
                }
            }
            foreach ($postFilterLanguage as $language) {
                if (!in_array($language, $oldSettingLanguages)) {
                    UserPostLanguage::create([
                        'user_id' => $userId,
                        'language_code' => $language,
                    ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollback();
        }
    }

    public static function setDefaultTopPageLangNotAuth($detectedCountry)
    {
        $topPageLanguage = ($detectedCountry == 'VN') ? 'vi' : 'en';
        set_cookie('topPageLanguage', $topPageLanguage, time() + self::COOKIE_TIME);
        Session::put('topPageLanguage', $topPageLanguage);

        return $topPageLanguage;
    }

    public static function setDefaultPostFilterLangNotAuth($detectLanguage)
    {
        $postFilterLanguage = in_array('en', $detectLanguage) ?
            $detectLanguage : array_merge(['en'], $detectLanguage);
        set_cookie('postFilterLanguage', serialize($postFilterLanguage), time() + self::COOKIE_TIME);
        Session::put('postFilterLanguage', $postFilterLanguage);

        return $postFilterLanguage;
    }

    public static function getSystemLang()
    {
        return is_null(get_cookie('systemLang'))
            ? (empty(App::getLocale()) ? 'en' : App::getLocale())
            : get_cookie('systemLang');
    }

    public static function getTopPageLang()
    {
        $topPageLanguage = @unserialize(get_cookie('topPageLanguage'));
        return ($topPageLanguage != false) ?
            (Session::has('topPageLanguage') ?
                Session::get('topPageLanguage') : 'en') :
                    $topPageLanguage;
    }

    public static function getPostFilterLanguage()
    {
        $encoded = unserialize(get_cookie('postFilterLanguage'));
        return ($encoded == false) ? (Session::has('postFilterLanguage') ? 
                                        Session::get('postFilterLanguage') : ['en']) : 
                        $encoded;
    }

    public static function setSystemLang($lang)
    {
        if (!is_null(get_cookie('systemLang'))) {
            delete_cookie('systemLang');
        }
        return set_cookie('systemLang', $lang, time() + self::COOKIE_TIME);
    }

    public static function setTopPageLang($lang)
    {
        if (!is_null(get_cookie('topPageLanguage'))) {
            delete_cookie('topPageLanguage');
        }

        return set_cookie('topPageLanguage', $lang, time() + self::COOKIE_TIME);
    }

    public static function setPostFilterLanguage($lang)
    {
        if (!is_null(get_cookie('postFilterLanguage'))) {
            delete_cookie('postFilterLanguage');
        }

        return set_cookie('postFilterLanguage', serialize($lang), time() + self::COOKIE_TIME);
    }

    public static function getOptionLangViEn()
    {
        $lang = Config::get('detect_language.code');

        return array_only($lang, ['vi', 'en']);
    }

    public static function getSystemLangOptions()
    {
        $lang = Config::get('detect_language.code');

        return array_only($lang, ['vi', 'en', 'ja']);
    }

    public static function getSystemLangMinOptions()
    {
        return [
            'en' => 'English',
            'vi' => 'Tiếng Việt',
            'ja' => '日本語',
        ];
    }

    public static function getLangStatistic($type)
    {
        $result = '';
        switch ($type) {
            case self::POST_FILTER_LANG:
                $languageStatistic = UserPostLanguage::select(DB::raw('count(user_id) as total_user'), 'language_code')
                                                    ->whereRaw('user_post_languages.user_id in (select id from users)')
                                                    ->groupBy('language_code')
                                                    ->orderBy('total_user', 'desc')
                                                    ->lists('total_user', 'language_code');
                break;
            case self::SYSTEM_LANG:
                $languageStatistic = Setting::select(DB::raw('count(user_id) as total_user'), 'lang')
                                            ->whereRaw('settings.user_id in (select id from users)')
                                            ->groupBy('lang')
                                            ->orderBy('total_user', 'desc')
                                            ->lists('total_user', 'lang');
                break;
            case self::DEFAULT_USER_POST_LANG:
                $languageStatistic = Setting::select(DB::raw('count(user_id) as total_user'), 'default_post_language')
                                            ->whereRaw('settings.user_id in (select id from users)')
                                            ->groupBy('default_post_language')
                                            ->orderBy('total_user', 'desc')
                                            ->lists('total_user', 'default_post_language');
                break;
            case self::PUBLISHED_POST_LANG:
                $languageStatistic = Post::select(DB::raw('count(id) as total_posts'), 'language_code')
                                        ->whereRaw('posts.user_id in (select id from users)')
                                        ->whereNotNull('published_at')
                                        ->whereNull('deleted_at')
                                        ->groupBy('language_code')
                                        ->orderBy('total_posts', 'desc')
                                        ->lists('total_posts', 'language_code');
                break;

            default:
                $languageStatistic = UserPostLanguage::select(DB::raw('count(user_id) as total_user'), 'language_code')
                                                    ->whereRaw('user_post_languages.user_id in (select id from users)')
                                                    ->groupBy('language_code')
                                                    ->orderBy('total_user', 'desc')
                                                    ->lists('total_user', 'language_code');
                break;
        }

        //for only Basa Jawa (jv / jw code)
        $languageStatistic = self::mergeDuplicateStatisticLang($languageStatistic, 'jw', 'jv');
        //end for basa jawa
        //for only Vietnamese (vi / vn code)
        $languageStatistic = self::mergeDuplicateStatisticLang($languageStatistic, 'vn', 'vi');
        //end for Vietnamese system language setting

        $languageArr = Config::get('detect_language.code');
        $languageArr[UserPostLanguage::SETTING_ALL_LANGUAGES] = trans('labels.language_manager.all_lang');
        if ($type == self::PUBLISHED_POST_LANG) {
            $totalCount = Post::whereNotNull('published_at')
                            ->whereNull('deleted_at')
                            ->count();
            //only for post languages statistic
            $languageArr[null] = trans('labels.language_manager.no_set');
        } else {
            $totalCount = User::withTrashed()->count();
        }

        foreach ($languageStatistic as $langCode => $langCount) {
            if (isset($languageArr[$langCode])) {
                $result[] = [
                    'language' => $languageArr[$langCode],
                    'langCount' => $langCount,
                    'percent' => round($langCount / $totalCount, 4) * 100,
                ];
            }
        }

        return $result;
    }

    public static function mergeDuplicateStatisticLang($arrayLang, $fromLang, $toLang)
    {
        $arrayLang[$fromLang] = isset($arrayLang[$fromLang]) ? $arrayLang[$fromLang] : 0;
        $arrayLang[$toLang] = isset($arrayLang[$toLang]) ? $arrayLang[$toLang] : 0;
        $arrayLang[$toLang] = $arrayLang[$fromLang] + $arrayLang[$toLang];
        unset($arrayLang[$fromLang]);
        if ($arrayLang[$toLang] == 0) {
            unset($arrayLang[$toLang]);
        }

        return $arrayLang;
    }

    public static function getFilterLangOption()
    {
        return [
            self::POST_FILTER_LANG => trans('labels.language_manager.post_filter'),
            self::SYSTEM_LANG => trans('labels.language_manager.system_lang'),
            self::DEFAULT_USER_POST_LANG => trans('labels.language_manager.default_post_language'),
            self::PUBLISHED_POST_LANG => trans('labels.language_manager.published_posts_language'),
        ];
    }
}
