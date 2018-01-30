<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_block', function (Blueprint $table) {
            $table->increments(\App\Model\HomeBlock::ID);
            $table->string(\App\Model\HomeBlock::LABEL, 255);
            $table->string(\App\Model\HomeBlock::PREVIEW, 255);
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
        Schema::dropIfExists('home_block');
    }
}
