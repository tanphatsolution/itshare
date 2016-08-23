<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWikiTableDropColumnContentAndUserId extends Migration
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
            $table->dropColumn('content');
            $table->dropColumn('user_id');
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
            $table->text('content')->after('title');
            $table->integer('user_id')->after('encrypted_group_id');
        });
    }
}
