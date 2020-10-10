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
            $table->string('guid')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('job')->nullable();
            $table->string('faculty_code')->nullable();
            $table->string('department_code')->nullable();
            $table->string('country')->default("Україна");
            $table->string('h_index')->nullable();
            $table->integer('scopus_autor_id')->nullable();
            $table->integer('scopus_researcher_id')->nullable();
            $table->string('orcid')->nullable();
            $table->string('academic_code')->nullable();
            $table->foreignId('roles_id')->default(1);
            $table->foreignId('categ_1')->nullable();
            $table->foreignId('categ_2')->nullable();
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
