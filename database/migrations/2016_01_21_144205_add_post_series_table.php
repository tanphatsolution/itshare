<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostSeriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_series', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->default(0);
            $table->integer('group_series_id')->default(0);
            $table->string('language_code')->nullable()->default(NULL);
            $table->datetime('published_at')->nullable()->default(NULL);
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
        Schema::drop('post_series');
    }

}
