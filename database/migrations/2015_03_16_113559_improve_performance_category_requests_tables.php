<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformanceCategoryRequestsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_requests', function () {
            DB::statement('ALTER TABLE `category_requests` DROP INDEX `category_requests_name_unique`');
            DB::statement('ALTER TABLE `category_requests` DROP INDEX `category_requests_short_name_unique`');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
