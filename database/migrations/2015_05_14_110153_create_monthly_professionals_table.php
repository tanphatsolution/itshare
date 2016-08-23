<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyProfessionalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_professionals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('monthly_theme_subject_id');
            $table->integer('monthly_theme_id');
            $table->string('url', 500);
            $table->integer('post_id');
            $table->integer('order');
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
        Schema::drop('monthly_professionals');
    }

}
