<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationsColumnSettingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('receive_newsletter')->default(1)->after('display_occupation_info');
            $table->boolean('receive_comment_notification')->default(1)->after('receive_newsletter');
            $table->boolean('receive_mention_notification')->default(1)->after('receive_comment_notification');
            $table->boolean('receive_follow_notification')->default(1)->after('receive_mention_notification');
            $table->boolean('receive_stock_notification')->default(1)->after('receive_follow_notification');
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
            $table->dropColumn('receive_newsletter');
            $table->dropColumn('receive_comment_notification');
            $table->dropColumn('receive_mention_notification');
            $table->dropColumn('receive_follow_notification');
            $table->dropColumn('receive_stock_notification');
        });
    }

}
