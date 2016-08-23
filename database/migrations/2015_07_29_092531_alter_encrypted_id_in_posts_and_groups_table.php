<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEncryptedIdInPostsAndGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `posts` MODIFY `encrypted_id` VARCHAR(255) COLLATE latin1_general_cs');
        DB::statement('ALTER TABLE `groups` MODIFY `encrypted_id` VARCHAR(255) COLLATE latin1_general_cs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `posts` MODIFY `encrypted_id` VARCHAR(255) COLLATE utf8_general_ci');
        DB::statement('ALTER TABLE `groups` MODIFY `encrypted_id` VARCHAR(255) COLLATE utf8_general_ci');
    }

}
