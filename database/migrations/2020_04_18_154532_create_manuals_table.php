<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manuals', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('number_volumes');
            $table->string('volume');
            $table->string('by_editing');
            $table->string('country');
            $table->string('city');
            $table->string('editor_name');
            $table->integer('number_pages');
            $table->integer('languages');
            $table->string('doi');
            $table->foreignId('publications_id');
            $table->timestamps();
        });

        Schema::table('manuals', function (Blueprint $table) {
            $table->index('publications_id');
            $table->foreign('publications_id')->references('id')->on('publications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manuals');
    }
}
