<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderColumnToGroupSeriesItems extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_series_items', function(Blueprint $table)
        {
            $table->integer('order_item')->after('type')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_series_items', function(Blueprint $table)
        {
            $table->dropColumn('order_item');
        });
    }

}
