<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClientEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_emails', function (Blueprint $table) {
           $table->integer('email_id')->unsigned();
           $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
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
        Schema::table('client_emails', function(Blueprint $table) {
            $table->dropForeign(['email_id']);
            $table->dropForeign(['client_id']);
        });
        Schema::dropIfExists('client_emails');
    }
}
