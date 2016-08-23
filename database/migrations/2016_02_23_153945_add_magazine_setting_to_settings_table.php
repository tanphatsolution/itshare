<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMagazineSettingToSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('receive_monthly_magazine')->default(1)->after('receive_mail_notification');
            $table->boolean('receive_weekly_magazine')->default(1)->after('receive_monthly_magazine');
            $table->boolean('receive_other_mail')->default(1)->after('receive_weekly_magazine');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('receive_monthly_magazine');
            $table->dropColumn('receive_weekly_magazine');
            $table->dropColumn('receive_other_mail');
        });
	}

}
