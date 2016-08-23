<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformancePostCategoriesTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_categories', function (Blueprint $table) {
            DB::statement('ALTER TABLE `post_categories` CHANGE COLUMN `post_id` `post_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `post_categories` CHANGE COLUMN `category_id` `category_id` INT(11) UNSIGNED NOT NULL');
            $table->index('post_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropIndex('post_categories_post_id_index');
            $table->dropIndex('post_categories_category_id_index');
        });
    }

}
