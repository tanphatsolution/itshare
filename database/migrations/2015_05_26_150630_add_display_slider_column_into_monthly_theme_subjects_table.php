<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplaySliderColumnIntoMonthlyThemeSubjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_theme_subjects', function ($table) {
            $table->tinyInteger('display_slider')->default(1)->after('img');
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
            $table->dropColumn('display_slider');
        });

    }

}
