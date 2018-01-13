<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlbumVisibleColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('album', function (Blueprint $table) {
            $table->boolean(\App\Model\Album::VISIBLE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('album', function (Blueprint $table) {
            $table->dropColumn(\App\Model\Album::VISIBLE);
        });
    }
}
