<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGroupSettingsTableAddEditPostFlagColumn extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_settings', function(Blueprint $table)
        {
            $table->tinyInteger('edit_post_flag')->default(0)->after('add_post_flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('group_settings', 'edit_post_flag')) {
            Schema::table('group_settings', function ($table) {
                $table->dropColumn('edit_post_flag');
            });
        }
    }
}
