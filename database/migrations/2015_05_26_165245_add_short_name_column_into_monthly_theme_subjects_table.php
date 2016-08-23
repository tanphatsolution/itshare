<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShortNameColumnIntoMonthlyThemeSubjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_theme_subjects', function ($table) {
            $table->string('short_name', 400)->after('theme_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_theme_subjects', function ($table) {
            $table->dropColumn('short_name');
        });
    }

}
