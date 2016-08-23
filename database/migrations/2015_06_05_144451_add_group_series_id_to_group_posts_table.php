<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupSeriesIdToGroupPostsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_posts', function(Blueprint $table)
        {
            $table->integer('group_series_id')->nullable()->after('post_id');
            DB::update('ALTER TABLE group_posts MODIFY post_id INT(11) DEFAULT NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_posts', function(Blueprint $table)
        {
            $table->dropColumn('group_series_id');
        });
    }

}
