<?php namespace App\Data\Blog;

use App\Facades\Authority;
use App\Services\LanguageService;
use Auth;
use Cache;

class UserPostLanguage extends BaseModel
{

    CONST SETTING_ALL_LANGUAGES = 'all';

    protected $table = 'user_post_languages';

    protected $guarded = ['id'];

    public static $createRules = [
        'user_id' => 'required',
        'language_code' => 'required',
    ];

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public static function getCurrentUserLanguages()
    {
        if (Authority::check()) {
            $userLanguages = self::getCacheUserPostLanguages();
        } else {
            $userLanguages = LanguageService::getPostFilterLanguage();
        }

        if (is_string($userLanguages) && !Auth::check()) {
            return unserialize($userLanguages);
        }

        if (is_array($userLanguages)) {
            return $userLanguages;
        }

        return array($userLanguages);
    }

    protected static function getCacheUserPostLanguages()
    {
        $userLanguages = [];
        $user = Auth::user();
        $cache_key = 'user_' . $user->id . '_post_language';
        
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        LanguageService::setDefaultLanguage();
        $langs = $user->userPostLanguages()->get(['language_code']);
        
        foreach ($langs as $key => $item) {
            $userLanguages[] = $item->language_code;
        }

        Cache::put($cache_key, $userLanguages, 360);
        return $userLanguages;
    }
}
