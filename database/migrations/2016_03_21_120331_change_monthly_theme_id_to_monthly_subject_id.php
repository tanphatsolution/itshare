<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeMonthlyThemeIdToMonthlySubjectId extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('contests', 'monthly_theme_subject_id')) {
            Schema::table('contests', function (Blueprint $table) {
                $table->integer('monthly_theme_subject_id')->after('title');
                $table->dropColumn('monthly_theme_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('contests', 'monthly_theme_subject_id')) {
            Schema::table('contests', function (Blueprint $table) {
                $table->dropColumn('monthly_theme_subject_id');
            });
        }
    }

}
