<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGroupSettingsTableModifyAddMemberFlagColumn extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_settings', function()
        {
            DB::update('ALTER TABLE group_settings MODIFY add_member_flag TINYINT(4) DEFAULT 0 NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_settings', function()
        {
            DB::update('ALTER TABLE group_settings MODIFY add_member_flag BOOLEAN DEFAULT 0 NOT NULL');
        });
    }

}
