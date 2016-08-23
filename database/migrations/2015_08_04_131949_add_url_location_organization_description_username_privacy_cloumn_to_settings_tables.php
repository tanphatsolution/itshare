<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlLocationOrganizationDescriptionUsernamePrivacyCloumnToSettingsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function(Blueprint $table)
        {
            $table->tinyInteger('display_username_info')->after('display_email')->default(1);
            $table->tinyInteger('display_phone_info')->after('display_occupation_info')->default(0);
            $table->tinyInteger('display_url_info')->after('display_occupation_info')->default(1);
            $table->tinyInteger('display_location_info')->after('display_occupation_info')->default(1);
            $table->tinyInteger('display_organization_info')->after('display_occupation_info')->default(1);
            $table->tinyInteger('display_description_info')->after('display_occupation_info')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function(Blueprint $table)
        {
            $table->dropColumn('display_username_info');
            $table->dropColumn('display_phone_info');
            $table->dropColumn('display_url_info');
            $table->dropColumn('display_location_info');
            $table->dropColumn('display_organization_info');
            $table->dropColumn('display_description_info');
        });
    }

}
