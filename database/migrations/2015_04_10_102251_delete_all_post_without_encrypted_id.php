<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAllPostWithoutEncryptedId extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App\Data\Blog\Post::whereNull('encrypted_id')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
