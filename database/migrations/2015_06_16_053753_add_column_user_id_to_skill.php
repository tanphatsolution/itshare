<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserIdToSkill extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skill_categories', function($table)
        {
            $table->integer('user_id')->nullable()->after('short_name')->default(null);
        });
        Schema::table('skills', function($table)
        {
            $table->integer('user_id')->nullable()->after('short_name')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('skill_categories', function($table)
        {
            $table->dropColumn('user_id');
        });
        Schema::table('skills', function($table)
        {
            $table->dropColumn('user_id');
        });
    }

}
