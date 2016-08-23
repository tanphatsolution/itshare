<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFacebookGoogleGithubPrivacyInSettingsTables extends Migration
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
            $table->tinyInteger('display_facebook_info')->after('display_phone_info')->default(1);
            $table->tinyInteger('display_google_info')->after('display_phone_info')->default(1);
            $table->tinyInteger('display_github_info')->after('display_phone_info')->default(1);
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
            $table->dropColumn('display_facebook_info');
            $table->dropColumn('display_google_info');
            $table->dropColumn('display_github_info');
        });
    }

}
