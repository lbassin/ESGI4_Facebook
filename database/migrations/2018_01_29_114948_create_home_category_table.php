<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_category', function (Blueprint $table) {
            $table->increments(\App\Model\HomeCategory::ID);
            $table->string(\App\Model\HomeCategory::LABEL, 255);
            $table->string(\App\Model\HomeCategory::PREVIEW, 255);
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
        Schema::dropIfExists('home_category');
    }
}
