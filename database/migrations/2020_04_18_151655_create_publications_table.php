<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('science_type_id')->nullable(); // Scopus WoS
            $table->foreignId('publication_type_id'); // тип публікації
            $table->string('snip'); // Коефіцієнт впливовості SNIP журналу SCOPUS
            $table->string('impact_factor'); // Коефіцієнт впливовості імпакт-фактор журналу WoS
            $table->string('quartil_scopus')->nullable(); // Квартиль журналу БД Scopus
            $table->string('quartil_wos')->nullable(); // Квартиль журналу БД WoS
            $table->integer('sub_db_index'); // Підбаза WoS
            $table->string('scie')->nullable(); // входить до SCIE
            $table->string('ssci')->nullable(); // входять до SSCI
            $table->string('nature_index')->nullable(); // обліковуються рейтингом Nature Index
            $table->string('nature_scince')->nullable(); // у журналах Nature Scince
            $table->string('other_organizations')->nullable(); // за співавторством з представниками інших організацій
            $table->string('forbes_fortune')->nullable(); // входять до списків Forbes та Fortune
            $table->string('international_patents')->nullable(); // процитовані у міжнародних патентах

            $table->integer('year')->nullable(); // Рік
            $table->integer('number')->nullable(); // Номер (том)
            $table->integer('pages')->nullable(); // Кількість сторінок
            $table->string('country')->nullable(); // Країна
            $table->string('name_magazine')->nullable(); // Назва журналу

            $table->integer('number_volumes')->nullable(); // Кількість томів
            $table->string('by_editing')->nullable(); // За редакцією
            $table->string('city')->nullable(); // Місто видання
            $table->string('editor_name')->nullable(); // Назва редакції
            $table->boolean('languages')->nullable(); // Опубліковано мовами ОЕСР та ЄС
            $table->integer('number_certificate')->nullable(); // № свідоцтва/рішення/патенту
            $table->string('applicant')->nullable(); // Заявник
            $table->string('date_application')->nullable(); // Дата подачі
            $table->string('date_publication')->nullable(); // Дата публікації
            $table->boolean('commerc_university')->nullable(); // Комерціалізовано університетом
            $table->boolean('commerc_employees')->nullable(); // Комерціалізовано штатними співробітниками університету
            $table->boolean('access_mode')->nullable(); // Режим доступу
            $table->string('mpk')->nullable(); // МПК
            $table->integer('application_number')->nullable(); // № заявки
            $table->integer('newsletter_number')->nullable(); // № бюлетеня
            $table->string('name_conference')->nullable(); // Назва конференції
            $table->string('publisher')->nullable(); // Видавництво
            $table->string('doi')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::table('publications', function (Blueprint $table) {
            $table->index('science_type_id');
            $table->foreign('science_type_id')->references('id')->on('science_type')->onDelete('cascade');
        });

        Schema::table('publications', function (Blueprint $table) {
            $table->index('publication_type_id');
            $table->foreign('publication_type_id')->references('id')->on('publication_type')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
