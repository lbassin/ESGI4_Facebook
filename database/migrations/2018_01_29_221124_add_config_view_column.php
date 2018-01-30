<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfigViewColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_block', function (Blueprint $table) {
            $table->string(\App\Model\HomeBlock::CONFIG_FILE, 255);
            $table->string(\App\Model\HomeBlock::VIEW_FILE, 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_block', function (Blueprint $table) {
            $table->dropColumn(\App\Model\HomeBlock::CONFIG_FILE);
            $table->dropColumn(\App\Model\HomeBlock::VIEW_FILE);
        });
    }
}
