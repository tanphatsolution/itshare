<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedToGroupPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('group_posts', function(Blueprint $table)
		{
			$table->boolean('approved')->default(0)->after('privacy_flag');
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
			$table->dropColumn('approved');
		});
	}

}
