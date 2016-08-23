<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformanceStocksTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            DB::statement('ALTER TABLE `stocks` CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `stocks` CHANGE COLUMN `post_id` `post_id` INT(11) UNSIGNED NOT NULL');
            $table->index('user_id');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex('stocks_user_id_index');
            $table->dropIndex('stocks_post_id_index');
        });
    }

}
