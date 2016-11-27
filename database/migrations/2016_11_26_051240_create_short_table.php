<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 12)->unique(); // Main short code for a shortened URL. Used during request with shortened URL.
            $table->integer('user')->index(); // User id, used when looking up all shortened URLs, by user.
            $table->integer('url_mobile');
            $table->integer('url_tablet');
            $table->integer('url_desktop');
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
        Schema::dropIfExists('short_urls');
    }
}
