<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger(\App\Model\Review::SOURCE_ID);
            $table->bigInteger(\App\Model\Review::REVIEWER_ID);
            $table->integer(\App\Model\Review::VISIBLE);
            $table->timestamps();
            $table->unique([\App\Model\Review::SOURCE_ID, \App\Model\Review::REVIEWER_ID]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review');
    }
}
