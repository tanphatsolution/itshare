<?php

use Illuminate\Database\Migrations\Migration;

class AlterTableFeedbacksAllowUserNull extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `feedbacks` MODIFY `user_id` INT(11) UNSIGNED');
        DB::statement('ALTER TABLE `feedbacks` MODIFY `username` VARCHAR (255)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `feedbacks` MODIFY `user_id` INT(11) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `feedbacks` MODIFY `username` VARCHAR (255) NOT NULL');
    }

}
