<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSkillsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::rename('skills', 'user_skills');
        Schema::table('user_skills', function ($table) {
            $table->integer('skill_id')->default(0)->after('user_id');
            $table->dropColumn('name');
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
        Schema::rename('user_skills', 'skills');
        Schema::table('skills', function ($table) {
            $table->dropColumn('skill_id');
            $table->string('name')->after('user_id');
        });
    }

}
