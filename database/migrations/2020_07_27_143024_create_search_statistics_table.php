<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source');
            $table->string('phrase');
            $table->integer('amount')->default(0);
            $table->dateTime('last_update_date');
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
        Schema::dropIfExists('search_statistics');
    }
}
