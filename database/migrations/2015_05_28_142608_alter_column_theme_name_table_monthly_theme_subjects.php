<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnThemeNameTableMonthlyThemeSubjects extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::update('ALTER TABLE monthly_theme_subjects MODIFY theme_name VARCHAR(100)');
        DB::update('ALTER TABLE monthly_theme_subjects MODIFY short_name VARCHAR(80)');
        DB::update('ALTER TABLE monthly_theme_languages MODIFY name VARCHAR(100)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::update('ALTER TABLE monthly_theme_subjects MODIFY theme_name VARCHAR(500)');
        DB::update('ALTER TABLE monthly_theme_subjects MODIFY short_name VARCHAR(400)');
        DB::update('ALTER TABLE monthly_theme_languages MODIFY name VARCHAR(500)');
    }

}
