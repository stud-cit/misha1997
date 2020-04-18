<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->integer('guid');
            $table->string('job');
            $table->string('country');
            $table->string('h_index');
            $table->integer('scopus_autor_id');
            $table->integer('scopus_researcher_id');
            $table->string('orcid');
            $table->string('department');
            $table->string('faculty');
            $table->integer('is_student');
            $table->string('academic_code');
            $table->string('email');
            $table->foreignId('roles_id');
            $table->timestamps();
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->index('roles_id');
            $table->foreign('roles_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
}
