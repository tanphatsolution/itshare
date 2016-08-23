<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyThemeSubjects extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_theme_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('theme_name', 500);
            $table->string('img', 255);
            $table->integer('publish_month');
            $table->integer('publish_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('monthly_theme_subjects');
    }

}
