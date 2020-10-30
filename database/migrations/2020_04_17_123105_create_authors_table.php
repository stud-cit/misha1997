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
            $table->string('guid')->nullable(); // guid кабінету
            $table->string('name'); // ім'я
            $table->string('date_bth')->nullable(); // дата народження
            $table->string('job')->nullable(); // місце роботи
            $table->string('faculty_code')->nullable(); // код факультету
            $table->string('department_code')->nullable(); // код кафедри
            $table->string('country')->default("Україна"); // країна
            $table->integer('h_index')->nullable(); // Індекс Гірша БД Scopus БД WoS
            $table->integer('scopus_autor_id')->nullable(); // Індекс Гірша БД Scopus
            $table->integer('scopus_researcher_id')->nullable(); // Research ID
            $table->string('orcid')->nullable(); // ORCID
            $table->string('academic_code')->nullable(); // академічна група
            $table->foreignId('roles_id')->default(1); // роль
            $table->foreignId('categ_1')->nullable(); // categ_1 кабінету
            $table->foreignId('categ_2')->nullable(); // categ_2 кабінету
            $table->foreignId('token')->nullable(); // token кабінету
            $table->boolean('forbes_fortune')->nullable(); // Входить до списків Forbes та Fortune
            $table->boolean('five_publications')->default(0); // 5 або більше публікацій в Scopus та/або WoS
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
