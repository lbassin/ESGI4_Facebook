<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album', function (Blueprint $table) {
            $table->bigInteger(\App\Model\Album::ID);
            $table->integer(\App\Model\Album::TEMPLATE_ID);
            $table->string(\App\Model\Album::TITLE, 255);
            $table->string(\App\Model\Album::DESCRIPTION, 512);
            $table->string(\App\Model\Album::URL, 127);
            $table->boolean(\App\Model\Album::HIDE_NEW);
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
        Schema::dropIfExists('album');
    }
}
