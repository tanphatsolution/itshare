<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThemeColumnIntoPostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function ($table) {
            $table->integer('monthly_theme_subject_id')->after('blocked');
            $table->integer('monthly_theme_id')->after('monthly_theme_subject_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function ($table) {
            $table->dropColumn('monthly_theme_subject_id');
            $table->dropColumn('monthly_theme_id');
        });
    }

}
