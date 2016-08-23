<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnViewCountInGroupSeries extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_series', function(Blueprint $table)
        {
            $table->integer('views_count')->after('group_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_series', function(Blueprint $table)
        {
            $table->dropColumn('views_count');
        });
    }

}
