<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformanceStockRankingWeeklyTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_ranking_weekly', function (Blueprint $table) {
            DB::statement('ALTER TABLE `stock_ranking_weekly` CHANGE COLUMN `post_id` `post_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `stock_ranking_weekly` CHANGE COLUMN `stocks_count` `stocks_count` INT(11) UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `stock_ranking_weekly` CHANGE COLUMN `year` `year` INT(11) UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `stock_ranking_weekly` CHANGE COLUMN `week` `week` INT(11) UNSIGNED NOT NULL DEFAULT 0');
            $table->index('post_id');
            $table->index(['year', 'week']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_ranking_weekly', function (Blueprint $table) {
            $table->dropIndex('stock_ranking_weekly_post_id_index');
            $table->dropIndex('stock_ranking_weekly_year_week_index');
        });
    }

}
