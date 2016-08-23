<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformancePostsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            DB::statement('ALTER TABLE `posts` CHANGE COLUMN `stocks_count` `stocks_count` INT(11) UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `posts` CHANGE COLUMN `views_count` `views_count` INT(11) UNSIGNED NOT NULL DEFAULT 0');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_user_id_index');
        });
    }

}
