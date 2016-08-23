<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTermScoreEndToContests extends Migration {

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        if (!Schema::hasColumn('contests', 'term_score_end')) {
            Schema::table('contests', function (Blueprint $table) {
                $table->timestamp('term_score_end')->after('term_end');
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
        if (Schema::hasColumn('contests', 'term_score_end')) {
            Schema::table('contests', function (Blueprint $table) {
                $table->dropColumn('term_score_end');
            });
        }
    }
}