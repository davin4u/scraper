<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_id')->index();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('location_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->text('location_description')->nullable();
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
        Schema::dropIfExists('store_locations');
    }
}
