<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImagesColumnIntoProfessionalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_professionals', function ($table) {
            $table->string('professional_img', 255)->after('order');
            $table->string('slider_img', 255)->after('professional_img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_professionals', function ($table) {
            $table->dropColumn('professional_img');
            $table->dropColumn('slider_img');
        });
    }

}
