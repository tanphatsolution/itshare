<?php namespace App\Libraries;

use Config;
use Request;

class DetectLanguageByIpHelper
{
    protected $country = null;

    CONST DEFAULT_COUNTRY = 'VN';

    public function detectCountry()
    {
        $url = 'http://www.geoplugin.net/php.gp?ip=' . Request::ip();
        $detectResult = @file_get_contents($url);

        if ($detectResult === FALSE) {
            $country = self::DEFAULT_COUNTRY;
        } else {
            $getContent = unserialize($detectResult);
            $country = empty($getContent['geoplugin_countryCode']) ? self::DEFAULT_COUNTRY : $getContent['geoplugin_countryCode'];
        }
        $this->country = $country;
        return $country;
    }

    public function detectLanguage()
    {
        $country2Lang = Config::get('country_language');
        $languagesInDB = Config::get('detect_language.code');
        $defaultLangs = ['vi', 'en'];
        $languages = ($this->country == null) ? $defaultLangs :
            (isset($country2Lang[$this->country]) ? $country2Lang[$this->country] : $defaultLangs);

        $languagesCode = [];

        foreach ($languagesInDB as $key => $language) {
            $languagesCode[] = $key;
        }

        foreach ($languages as $key => $language) {
            if (!in_array($language, $languagesCode)) {
                unset($languages[$key]);
            }
        }

        return $languages;
    }
}
