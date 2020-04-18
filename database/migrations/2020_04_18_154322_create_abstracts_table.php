<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbstractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abstracts', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('name_conference');
            $table->string('publisher');
            $table->integer('pages');
            $table->string('country');
            $table->string('city');
            $table->string('doi');
            $table->foreignId('publications_id');
            $table->timestamps();
        });

        Schema::table('abstracts', function (Blueprint $table) {
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
        Schema::dropIfExists('abstracts');
    }
}
