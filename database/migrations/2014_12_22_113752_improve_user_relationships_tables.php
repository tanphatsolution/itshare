<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImproveUserRelationshipsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_relationships', function (Blueprint $table) {
            DB::statement('ALTER TABLE `user_relationships` CHANGE COLUMN `follower_id` `follower_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `user_relationships` CHANGE COLUMN `followed_id` `followed_id` INT(11) UNSIGNED NOT NULL');
            $table->index('follower_id');
            $table->index('followed_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_relationships', function (Blueprint $table) {
            $table->dropIndex('user_relationships_follower_id_index');
            $table->dropIndex('user_relationships_followed_id_index');
        });
    }

}
