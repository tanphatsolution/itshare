<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyThemeLanguagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_theme_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('monthly_theme_id');
            $table->string('name', 500);
            $table->string('language_code', 4);
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
        Schema::drop('monthly_theme_languages');
    }

}
