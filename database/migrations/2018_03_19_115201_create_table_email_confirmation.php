<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmailConfirmation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_confirmation', function(Blueprint $table){
	       $table->increment('id');
	       $table->integer('user_id');
	       $table->foreign('user_id')->references('id')->on('users');
	       $table->tinyInteger('confirm')->default(0);
	       $table->timestamp('confirmed_at');
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
        //
    }
}
