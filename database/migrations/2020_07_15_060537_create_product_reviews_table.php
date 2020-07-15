<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->index()->nullable();
            $table->bigInteger('author_id')->index();
            $table->string('title');
            $table->string('url');
            $table->date('published_at');
            $table->string('pros')->nullable();
            $table->string('cons')->nullable();
            $table->integer('likes_count')->default(0);
            $table->longText('body')->nullable();
            $table->text('summary')->nullable();
            $table->date('bought_at')->nullable();
            $table->float('rating')->default(0);
            $table->tinyInteger('i_recommend')->default(0);
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
        Schema::dropIfExists('product_reviews');
    }
}
