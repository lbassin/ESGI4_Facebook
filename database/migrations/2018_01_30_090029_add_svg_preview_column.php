<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSvgPreviewColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_block', function (Blueprint $table) {
            $table->text(\App\Model\HomeBlock::SVG_PREVIEW);
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
            $table->dropColumn(\App\Model\HomeBlock::SVG_PREVIEW);
        });
    }
}
