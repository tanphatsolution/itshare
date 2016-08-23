<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnShortnameTableGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groups', 'shortname')) {
            Schema::table('groups', function () {
                DB::statement('ALTER TABLE groups CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
                DB::statement('ALTER TABLE groups MODIFY shortname VARCHAR(255)');
            });
        }
    }

    public function down()
    {
        
    }
}

