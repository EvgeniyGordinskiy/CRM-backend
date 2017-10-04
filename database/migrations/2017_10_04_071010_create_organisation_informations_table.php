<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisationInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisation_informations', function (Blueprint $table) {
           $table->increments('id');
           $table->string('description');
           $table->integer('organisation_type_id')->unsigned()->nullable();
           $table->foreign('organisation_type_id')->references('id')->on('organisation_type')->onDelete('set null');
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
        Schema::table('organisation_informations', function (Blueprint $table) {
            $table->dropForeign(['organisation_type_id']);
        });
        Schema::dropIfExists('organisation_informations');
    }
}
