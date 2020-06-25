<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsHasPublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors_has_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autors_id');
            $table->foreignId('publications_id');
        });

        Schema::table('authors_has_publications', function (Blueprint $table) {
            $table->index('autors_id');
            $table->foreign('autors_id')->references('id')->on('authors')->onDelete('cascade');
        });

        Schema::table('authors_has_publications', function (Blueprint $table) {
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
        Schema::dropIfExists('authors_has_publications');
    }
}
