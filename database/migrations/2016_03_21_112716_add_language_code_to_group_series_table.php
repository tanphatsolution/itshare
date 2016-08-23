<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguageCodeToGroupSeriesTable extends Migration {

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        if (!Schema::hasColumn('group_series', 'language_code')) {
            Schema::table('group_series', function (Blueprint $table) {
                $table->string('language_code', 15)->default('en')->after('group_id');
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
        if (Schema::hasColumn('group_series', 'language_code')) {
            Schema::table('group_series', function (Blueprint $table) {
                $table->dropColumn('language_code');
            });
        }
    }
}
