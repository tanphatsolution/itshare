<?php namespace App\Helper;

use App\Services\LanguageService;
use Carbon\Carbon;

class Home
{
    /**
     * get description 700 char
     * @param $content
     * @param int $limit
     * @return string
     */
    public static function getQuestionDescription($content, $limit = 700)
    {
        if (mb_strlen($content) >= $limit) {
            return mb_substr($content, 0, strpos($content, ' ', $limit)) . '...';
        }
        return $content;
    }

    /**
     * @param $date
     * @return string
     */
    public static function convertTime($date)
    {
        Carbon::setLocale(LanguageService::getSystemLang());
        return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }
}