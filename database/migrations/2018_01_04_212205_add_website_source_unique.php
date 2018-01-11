<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebsiteSourceUnique extends Migration
{
    const UNIQUE_INDEX_NAME = 'website_source_id_unique';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('website', function (Blueprint $table) {
            $table->unique(\App\Model\Website::SOURCE_ID, self::UNIQUE_INDEX_NAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('website', function (Blueprint $table) {
            $table->dropIndex(self::UNIQUE_INDEX_NAME);
        });
    }
}
