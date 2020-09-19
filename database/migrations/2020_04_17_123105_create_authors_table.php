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
            $table->string('name');
            $table->string('guid')->nullable();
            $table->string('job')->nullable();
            $table->string('country')->nullable();
            $table->string('h_index')->nullable();
            $table->integer('scopus_autor_id')->nullable();
            $table->integer('scopus_researcher_id')->nullable();
            $table->string('orcid')->nullable();
            $table->string('department')->nullable();
            $table->string('faculty')->nullable();
            $table->string('academic_code')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('roles_id')->default(1);
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
