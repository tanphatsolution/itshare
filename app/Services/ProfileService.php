<?php namespace App\Services;

use Config;

use App\Data\Blog\UserSkill;
use App\Data\Blog\Skill;
use App\Data\Blog\Profile;
use App\Data\Blog\SuggestSkill;
use App\Data\Blog\CitiesCountry;


class ProfileService
{
    CONST LOCATION_TYPE = 'cities';
    CONST LOCATION_STATUS_OK = 'OK';
    CONST LOCATION_GOOGLE_API_LINK = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?';
    CONST LOCATION_DETAIL_GOOGLE_API_LINK = 'https://maps.googleapis.com/maps/api/place/details/json?';

    /**
     * @param array $input
     * @return array
     */
    public static function updateByUserId($userId, $input)
    {
        $result = [
            'message' => trans('messages.profile.update_fail'),
        ];

        $profile = Profile::where('user_id', $userId)->first();

        if (is_null($profile) || !$profile->update($input['profile'])) {
            return $result;
        }

        self::updateSkill($userId, $input);

        $result['message'] = trans('messages.profile.update_success');
        return $result;
    }

    public static function updateSkill($userId, $input)
    {
        Skill::where('user_id', $userId)->delete();
        if (!isset($input['skill_name']) || empty($input['skill_name'])) {
            return true;
        }
        $skillNames = array();
        $skillYears = array();
        foreach ($input['skill_name'] as $index => $skillName) {
            $skillYears[convert_to_short_name($skillName)] = $input['skill_year'][$index];
            $skillNames[convert_to_short_name($skillName)] = $skillName;
        }
        foreach ($skillNames as $shortName => $name) {
            $suggestSkill = SuggestSkill::where('short_name', $shortName)->first();
            if (!$suggestSkill) {
                SuggestSkill::create([
                    'short_name' => $shortName,
                    'name' => $name,
                ]);
            }
            Skill::create([
                'user_id' => $userId,
                'name' => $name,
                'year' => $skillYears[$shortName],
            ]);
        }
        return true;
    }

    public static function getSkillYearOptions()
    {
        $years = [];
        $step = UserSkill::STEP_SKILL_YEAR;
        for ($i = $step; $i < UserSkill::MAX_SKILL_YEAR; $i = $i + $step) {
            $years[strval($i)] = $i;
        }
        return $years;
    }

    public static function sendMailConfirm($user, $lang = '', $workEmail = false)
    {
        if (is_null($user)) {
            return false;
        }

        $activeToken = str_random(100);

        if ($workEmail) {
            $user->update([
                'active_work_email_token' => $activeToken,
            ]);
        } else {
            $user->update([
                'active_token' => $activeToken,
            ]);
        }

        $data = [
            'id' => $user->id,
            'activeToken' => $activeToken,
            'username' => $user->username,
            'lang' => empty($lang) ? $user->setting->lang : $lang,
        ];

        $address = $workEmail ? $user->work_email : $user->email;
        $to = [
            'address' => $address,
            'name' => $user->name,
        ];
        $subject = trans('messages.user.email_confirm_subject_en', ['app_name' => Config::get('app.app_name')]);
        if ($lang == 'ja') {
            $subject = trans('messages.user.email_confirm_subject_ja', ['app_name' => Config::get('app.app_name')]);
        } elseif ($lang == 'vi') {
            $subject = trans('messages.user.email_confirm_subject_vi', ['app_name' => Config::get('app.app_name')]);
        }
        $layout = (!$workEmail) ? 'emails.user.confirm' : 'emails.user.confirm_change_email';
        MailService::send(Config::get('mail.from'), $to, $subject, $data, $layout, MailService::EMAIL_LOG_TYPE_CONFIRM);

        return true;
    }

    public static function getSuggestCities($keyword, $user)
    {
        $apiLink = self::LOCATION_GOOGLE_API_LINK;
        $type = self::LOCATION_TYPE;
        $language = $user->setting->lang;
        $key = Config::get('google.api_key');
        $data = 'input=' . $keyword . '&types=(' . $type . ')&language=' . $language . '&key=' . $key;

        $getSuggestCities = @file_get_contents($apiLink . $data);

        $suggestCities = [];

        if ($getSuggestCities) {
            $getSuggestCities = json_decode($getSuggestCities);

            if ($getSuggestCities->status == self::LOCATION_STATUS_OK) {
                $cities = $getSuggestCities->predictions;

                foreach ($cities as $city) {
                    $descriptionArray = [];
                    foreach ($city->terms as $term) {
                        $descriptionArray[] = $term->value;
                    }

                    $description = implode(', ', $descriptionArray);

                    $suggestCities[] = [
                        'place_id' => $city->place_id,
                        'description' => $description,
                    ];
                }
            }
        }

        return $suggestCities;
    }

    public static function saveCitiesCountry($input)
    {
        $cityCountry = CitiesCountry::where('place_id', $input['city_country_place_id'])
            ->where('lang', $input['lang'])
            ->first();
        if (!$cityCountry) {
            $languages = array_keys(LanguageService::getSystemLangMinOptions());
            $placeId = $input['city_country_place_id'];
            $types = self::LOCATION_TYPE;
            $key = Config::get('google.api_key');
            $fieldsTypeLocation = ['locality', 'administrative_area_level_1', 'country', 'political', 'colloquial_area'];
            foreach ($languages as $language) {
                $data = self::LOCATION_DETAIL_GOOGLE_API_LINK . 'placeid=' . $placeId . '&language=' . $language . '&types=' . $types . '&key=' . $key;
                $placeDetail = @file_get_contents($data);

                if ($placeDetail) {
                    $placeDetail = json_decode($placeDetail);
                    $addressComponents = $placeDetail->result->address_components;
                    $addressArray = [];

                    foreach ($addressComponents as $addressComponent) {
                        $checkType = true;
                        foreach ($addressComponent->types as $type) {
                            if (!in_array($type, $fieldsTypeLocation)) {
                                $checkType = false;
                            }
                        }
                        if ($checkType) {
                            $addressArray[] = $addressComponent->long_name;
                        }
                    }

                    $address = implode(', ', $addressArray);
                    CitiesCountry::create([
                        'lang' => $language,
                        'place_id' => $placeId,
                        'description' => $address,
                    ]);
                }
            }
        }

        return $input['city_country_place_id'];
    }

}
