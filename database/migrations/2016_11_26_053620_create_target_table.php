<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_urls', function (Blueprint $table) {
            $table->increments('id'); // Identified by id in the short_urls table.
            $table->text('url')->index(); // Checks if exists when adding/using a target URL.
            $table->integer('redirects');
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
        Schema::dropIfExists('target_urls');
    }
}
