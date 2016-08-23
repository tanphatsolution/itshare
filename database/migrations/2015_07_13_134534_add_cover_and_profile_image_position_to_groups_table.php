<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverAndProfileImagePositionToGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function(Blueprint $table)
        {
            $table->string('cover_img_crop_position', 255)->nullable()->after('cover_img');
            $table->string('profile_img_crop_position', 255)->nullable()->after('profile_img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function(Blueprint $table)
        {
            $table->dropColumn('cover_img_crop_position');
            $table->dropColumn('profile_img_crop_position');
        });
    }

}
