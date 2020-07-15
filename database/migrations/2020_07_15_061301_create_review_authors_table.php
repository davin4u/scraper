<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('platform');
            $table->integer('country_id')->index()->nullable();
            $table->integer('city_id')->index()->nullable();
            $table->integer('total_reviews')->default(0);
            $table->float('rating')->default(0);

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
        Schema::dropIfExists('review_authors');
    }
}
