<?php namespace App\Services;

use App\Data\Blog\Setting;

class SettingService
{
    const EMAIL = 1;
    const SOCIAL_ACCOUNTS = 2;
    const BASIC_PROFILE = 3;
    const OCCUPATION_INFO = 4;
    const URL = 5;
    const LOCATION = 6;
    const DESCRIPTION = 7;
    const ORGANIZATION = 8;
    const PHONE = 9;
    const FACEBOOK = 10;
    const GITHUB = 11;
    const GOOGLE = 12;
    const WORK_EMAIL = 13;
    const PUBLIC_PRIVACY = 1;
    const PRIVATE_PRIVACY = 0;

    /**
     * @var array All boolean fields in settings table
     */
    public static $toggleFields = [
        'display_email',
        'display_social_accounts',
        'display_basic_profile',
        'display_occupation_info',
    ];

    /**
     * @return array Information of each fields
     */
    public static function getFieldDetails()
    {
        return [
            'display_email' => 'Email',
            'display_social_accounts' => 'Social Accounts',
            'display_basic_profile' => 'Basic Profile',
            'display_occupation_info' => 'Occupation Information',
        ];
    }

    /**
     * Parse data submit from setting form
     * @param array $inputs
     * @return array
     */
    public static function prepareInput($inputs)
    {
        foreach (self::$toggleFields as $field) {
            $inputs[$field] = (isset($inputs[$field]) && $inputs[$field] === '1') ? 1 : 0;
        }

        return $inputs;
    }

    /**
     * Extract from field name to label that displayed in view
     * @param string $text
     * @return string
     */
    public static function getFieldLabel($text)
    {
        $details = self::getFieldDetails();
        return isset($details[$text]) ? $details[$text] : $text;
    }

    public static function getFieldNotificationDetails()
    {
        return [
            'receive_mention_notification' => 'receive_mention_notification',
            'receive_follow_notification' => 'receive_follow_notification',
            'receive_stock_notification' => 'receive_stock_notification',
            'receive_comment_notification' => 'receive_comment_notification',
            'receive_newsletter' => 'receive_newsletter',
            'receive_mail_notification' => 'receive_mail_notification',
            'receive_monthly_magazine' => 'receive_monthly_magazine',
            'receive_weekly_magazine' => 'receive_weekly_magazine',
            'receive_other_mail' => 'receive_other_mail',
        ];
    }

    public static function prepareInputNotification($inputs)
    {
        foreach (Setting::getNotificationSettingFields() as $field) {
            $inputs[$field] = (isset($inputs[$field])) ? 1 : 0;
        }
        return $inputs;
    }

    public static function getFieldNotificationLabel($text)
    {
        $details = self::getFieldNotificationDetails();
        return isset($details[$text]) ? $details[$text] : $text;
    }
}