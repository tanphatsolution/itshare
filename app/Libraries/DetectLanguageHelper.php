<?php namespace App\Libraries;

use \DetectLanguage\DetectLanguage;
use Config;
use Exception;

class DetectLanguageHelper
{
    public function __construct()
    {
        DetectLanguage::setApiKey(Config::get('detect_language.api_key'));
    }

    public function detect($content = '')
    {
        try {
            return DetectLanguage::detect($content);
        } catch (Exception $ex) {
            return null;
        }
    }

    public function simpleDetect($content = '')
    {
        try {
            return DetectLanguage::simpleDetect($content);
        } catch (Exception $ex) {
            return null;
        }
    }

    public function getStatus()
    {
        return DetectLanguage::getStatus();
    }

    public function setSecure()
    {
        DetectLanguage::setSecure(true);
    }
}
