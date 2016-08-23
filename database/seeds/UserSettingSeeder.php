<?php
use Illuminate\Database\Seeder;

class UserSettingSeeder extends Seeder
{

    public function run()
    {
        DB::statement('UPDATE `settings` SET
            `display_username_info` = 1,
            `display_social_accounts` = 1,
            `display_occupation_info` = 1,
            `display_description_info` = 1,
            `display_organization_info` = 1,
            `display_location_info` = 1,
            `display_url_info` = 1,
            `display_email` = 0,
            `display_phone_info` = 0
        ');
    }

}
