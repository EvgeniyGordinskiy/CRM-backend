<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClientAdresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_adresses', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('city_id')->unsigned();
           $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
           $table->integer('country_id')->unsigned();
           $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
           $table->string('street');
           $table->integer('client_id')->unsigned();
           $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
        Schema::table('client_adresses', function(Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['client_id']);
        });
        Schema::dropIfExists('client_adresses');
    }
}
