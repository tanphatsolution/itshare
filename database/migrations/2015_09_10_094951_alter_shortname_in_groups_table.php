<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShortnameInGroupsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `groups` MODIFY `shortname` VARCHAR(50) COLLATE latin1_general_cs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `groups` MODIFY `shortname` VARCHAR(50) COLLATE utf8_general_ci');
    }

}
