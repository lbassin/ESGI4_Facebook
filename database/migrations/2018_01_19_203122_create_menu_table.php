<?php

use App\Model\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMenuTable
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer(Menu::WEBSITE_ID);
            $table->integer(Menu::TEMPLATE_ID);
            $table->string(Menu::NAME, 127)->nullable();
            $table->boolean(Menu::ACCUEIL)->default(true);
            $table->boolean(Menu::ALBUMS)->default(true);
            $table->boolean(Menu::ARTICLES)->default(false);
            $table->boolean(Menu::EVENTS)->default(true);
            $table->boolean(Menu::REVIEWS)->default(true);
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
        Schema::dropIfExists('menu');
    }
}
