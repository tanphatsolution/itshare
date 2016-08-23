<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDomainColumnFromContestsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('contests', 'domain')) {
            Schema::table('contests', function(Blueprint $table) {
                $table->dropColumn('domain');
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
        if (!Schema::hasColumn('contests', 'domain')) {
            Schema::table('contests', function(Blueprint $table) {
                $table->string('domain', 64)->after('title');
            });
        }
    }

}
