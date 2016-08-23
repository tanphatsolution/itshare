<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMonthlyProfessionalsTableSetImgDefaultNotNull extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update('ALTER TABLE monthly_professionals MODIFY professional_img VARCHAR(255) DEFAULT NULL');
        DB::update('ALTER TABLE monthly_professionals MODIFY slider_img VARCHAR(255) DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('ALTER TABLE monthly_professionals MODIFY professional_img VARCHAR(255)');
        DB::update('ALTER TABLE monthly_professionals MODIFY slider_img VARCHAR(255)');
    }

}
