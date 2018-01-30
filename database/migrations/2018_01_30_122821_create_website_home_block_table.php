<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteHomeBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_home_block', function (Blueprint $table) {
            $table->increments(\App\Model\WebsiteHomeBlock::ID);
            $table->integer(\App\Model\WebsiteHomeBlock::WEBSITE_ID);
            $table->integer(\App\Model\WebsiteHomeBlock::BLOCK_ID);
            $table->text(\App\Model\WebsiteHomeBlock::CONFIG);
            $table->integer(\App\Model\WebsiteHomeBlock::ORDER);
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
        Schema::dropIfExists('website_home_block');
    }
}
