<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDefaultFlagToSkillCategoriesAndSkillsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skill_categories', function(Blueprint $table)
        {
            $table->boolean('default_flag')->default(0)->after('user_id');
        });

        Schema::table('skills', function(Blueprint $table)
        {
            $table->boolean('default_flag')->default(0)->after('user_id');
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
        Schema::table('skill_categories', function(Blueprint $table)
        {
            $table->dropColumn('default_flag');
        });
        
        Schema::table('skills', function(Blueprint $table)
        {
            $table->dropColumn('default_flag');
        });
    }

}
