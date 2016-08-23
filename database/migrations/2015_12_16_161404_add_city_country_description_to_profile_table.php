<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityCountryDescriptionToProfileTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function(Blueprint $table)
        {
            $table->text('city_country_description')->after('cities_country_id')->nullable();
        });

        DB::statement('ALTER TABLE `profiles` MODIFY `cities_country_id` VARCHAR(255) DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function(Blueprint $table)
        {
            $table->dropColumn('city_country_description');
        });

        DB::statement('ALTER TABLE `profiles` MODIFY `cities_country_id` INT(11) DEFAULT 0');
    }

}
