<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditSeriesFlagColumnToGroupSettingsTables extends Migration
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
            $table->tinyInteger('edit_series_flag')->after('approve_post_flag')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_settings', function(Blueprint $table)
        {
            $table->dropColumn('edit_series_flag');
        });
    }

}
