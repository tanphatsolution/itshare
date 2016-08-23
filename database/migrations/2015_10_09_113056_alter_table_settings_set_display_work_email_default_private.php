<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSettingsSetDisplayWorkEmailDefaultPrivate extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `settings` MODIFY `display_work_email` tinyint(4) DEFAULT 0 NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `settings` MODIFY `display_work_email` tinyint(4) DEFAULT 1 NOT NULL');
    }

}
