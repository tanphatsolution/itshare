<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnShortNameToTableGroups extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function(Blueprint $table) {
            $table->string('shortname', 50)->after('name');
        });
        DB::update('UPDATE groups set shortname = encrypted_id WHERE trim(shortname) is null OR trim(shortname) = ""');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('groups', function(Blueprint $table) {
            $table->dropColumn('shortname');
        });
    }

}
