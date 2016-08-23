<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformanceFollowCategoriesTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('follow_categories', function (Blueprint $table) {
            DB::statement('ALTER TABLE `follow_categories` CHANGE COLUMN `category_id` `category_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `follow_categories` CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL');
            $table->index('category_id');
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
        Schema::table('follow_categories', function (Blueprint $table) {
            $table->dropIndex('follow_categories_user_id_index');
            $table->dropIndex('follow_categories_category_id_index');
        });
    }

}
