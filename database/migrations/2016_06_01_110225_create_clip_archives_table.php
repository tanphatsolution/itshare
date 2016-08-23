<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClipArchivesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clip_archives')) {
            Schema::create('clip_archives', function(Blueprint $table) {
                $table->engine = 'MyISAM';
                $table->unsignedInteger('id');
                $table->unsignedInteger('question_id');
                $table->unsignedInteger('user_id');

                $table->timestamps();

                $table->primary('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('clip_archives')) {
            Schema::drop('clip_archives');
        }
    }

}
