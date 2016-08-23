<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMonthlyThemeIdToContestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('contests', 'monthly_theme_id')) {
            Schema::table('contests', function($table) {
                $table->integer('monthly_theme_id');
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
        if (Schema::hasColumn('contests', 'monthly_theme_id')) {
            Schema::table('contests', function(Blueprint $table) {
                $table->dropColumn('monthly_theme_id');
            });
        }
    }

}
