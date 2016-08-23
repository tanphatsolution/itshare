<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformanceNotificationsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE `notifications` CHANGE COLUMN `sender_id` `sender_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `notifications` CHANGE COLUMN `recipient_id` `recipient_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `notifications` CHANGE COLUMN `comment_id` `comment_id` INT(11) UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `notifications` CHANGE COLUMN `post_id` `post_id` INT(11) UNSIGNED NOT NULL DEFAULT 0');
            $table->index('sender_id');
            $table->index('recipient_id');
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
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_sender_id_index');
            $table->dropIndex('notifications_recipient_id_index');
            $table->dropIndex('notifications_post_id_index');
        });
    }

}
