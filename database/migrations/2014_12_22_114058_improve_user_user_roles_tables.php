<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ImproveUserUserRolesTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_roles', function (Blueprint $table) {
            DB::statement('ALTER TABLE `user_roles` CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `user_roles` CHANGE COLUMN `role_id` `role_id` INT(11) UNSIGNED NOT NULL');
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
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropIndex('user_roles_user_id_index');
        });
    }

}
