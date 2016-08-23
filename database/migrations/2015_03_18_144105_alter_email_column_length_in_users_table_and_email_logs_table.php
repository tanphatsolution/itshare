<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmailColumnLengthInUsersTableAndEmailLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::update('ALTER TABLE users MODIFY email VARCHAR(255)');
        DB::update('ALTER TABLE email_logs MODIFY receiver VARCHAR(255)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::update('ALTER TABLE users MODIFY email VARCHAR(50)');
        DB::update('ALTER TABLE email_logs MODIFY receiver VARCHAR(50)');
    }

}
