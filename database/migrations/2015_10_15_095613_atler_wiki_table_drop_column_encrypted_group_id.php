<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AtlerWikiTableDropColumnEncryptedGroupId extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wiki', function(Blueprint $table)
        {
            $table->dropColumn('encrypted_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wiki', function(Blueprint $table)
        {
            $table->string('encrypted_group_id')->after('parent_id');
        });
    }

}
