<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScraperCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scraper_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('url');
            $table->bigInteger('user_id');
            $table->dateTime('scraping_started_at')->nullable();
            $table->dateTime('scraping_finished_at')->nullable();
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
        Schema::dropIfExists('scraper_categories');
    }
}
