<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PublicationTypeTable extends Migration
{
    function up()
    {
        Schema::create('publication_type', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->boolean('scopus_wos');
        });
    }

    function down()
    {
        Schema::dropIfExists('publication_type');
    }
}
