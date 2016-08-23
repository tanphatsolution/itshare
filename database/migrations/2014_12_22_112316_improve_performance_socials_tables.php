<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImprovePerformanceSocialsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('socials', function (Blueprint $table) {
            DB::statement('ALTER TABLE `socials` CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL');
            $table->index('user_id');
            $table->index('email');
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('socials', function (Blueprint $table) {
            $table->dropIndex('socials_user_id_index');
            $table->dropIndex('socials_email_index');
            $table->dropIndex('socials_uid_index');
        });
    }

}
