<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestDomainsTable extends Migration {

    public function up()
    {
        if (!Schema::hasTable('contest_domains')) {
            Schema::create('contest_domains', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('contest_id');
                $table->integer('domain_id');
                $table->timestamps();
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
        if (Schema::hasTable('contest_domains')) {
            Schema::drop('contest_domains');
        }
    }

}
