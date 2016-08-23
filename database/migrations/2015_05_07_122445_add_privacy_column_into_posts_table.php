<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivacyColumnIntoPostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('posts', 'privacy')) {
            Schema::table('posts', function ($table) {
                $table->tinyInteger('privacy')->after('blocked');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('posts', 'privacy')) {
            Schema::table('posts', function ($table) {
                $table->dropColumn('privacy');
            });
        }
    }

}
