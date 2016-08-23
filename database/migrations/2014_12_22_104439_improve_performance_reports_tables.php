<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformanceReportsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            DB::statement('ALTER TABLE `reports` CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `reports` CHANGE COLUMN `post_id` `post_id` INT(11) UNSIGNED NOT NULL');
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
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_user_id_index');
            $table->dropIndex('reports_post_id_index');
        });
    }

}
