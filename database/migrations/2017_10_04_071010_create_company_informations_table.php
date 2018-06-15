<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_informations', function (Blueprint $table) {
           $table->increments('id');
           $table->string('description');
           $table->integer('company_type_id')->unsigned()->nullable();
           $table->foreign('company_type_id')->references('id')->on('type_companies')->onDelete('set null');
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
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropForeign(['company_type_id']);
        });
        Schema::dropIfExists('company_informations');
    }
}
