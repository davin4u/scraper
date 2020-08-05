<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_product_id')->index();

            $table->string('name');
            $table->string('url');
            $table->string('sku')->nullable();
            $table->float('price')->nullable();
            $table->float('old_price')->nullable();
            $table->string('currency')->nullable();
            $table->tinyInteger('is_available')->default(0);

            $table->string('delivery_text')->nullable();
            $table->string('delivery_days')->nullable();
            $table->float('delivery_price')->nullable();

            $table->text('benefits')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->longText('description')->nullable();

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
        Schema::dropIfExists('store_product_details');
    }
}
