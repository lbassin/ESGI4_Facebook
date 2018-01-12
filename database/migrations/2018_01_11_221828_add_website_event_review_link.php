<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebsiteEventReviewLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event', function (Blueprint $table) {
            $table->integer(\App\Model\Event::WEBSITE_ID);
        });

        Schema::table('review', function (Blueprint $table) {
            $table->integer(\App\Model\Review::WEBSITE_ID);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(\App\Model\Event::WEBSITE_ID);
        });

        Schema::table('review', function (Blueprint $table) {
            $table->dropColumn(\App\Model\Review::WEBSITE_ID);
        });
    }
}
