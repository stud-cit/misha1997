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
            $table->float('snip', 8, 2)->nullable(); // Коефіцієнт впливовості SNIP журналу SCOPUS
            $table->string('impact_factor')->nullable(); // Коефіцієнт впливовості імпакт-фактор журналу WoS
            $table->string('quartil_scopus')->nullable(); // Квартиль журналу БД Scopus
            $table->string('quartil_wos')->nullable(); // Квартиль журналу БД WoS
            $table->boolean('sub_db_scie')->default(0); // Підбаза WoS - SCIE
            $table->boolean('sub_db_ssci')->default(0); // Підбаза WoS - SSCI
            $table->integer('year')->nullable(); // Рік
            $table->string('number')->nullable(); // Номер (том)
            $table->string('pages')->nullable(); // Кількість сторінок
            $table->string('initials')->nullable(); // Прізвище та ініціали
            $table->string('country')->nullable(); // Країна
            $table->string('name_magazine')->nullable(); // Назва журналу
            $table->string('name_monograph')->nullable(); // Назва монографії
            $table->integer('number_volumes')->nullable(); // Кількість томів
            $table->string('by_editing')->nullable(); // За редакцією
            $table->string('city')->nullable(); // Місто видання
            $table->string('editor_name')->nullable(); // Назва редакції
            $table->boolean('languages')->nullable(); // Опубліковано мовами ОЕСР та ЄС
            $table->string('number_certificate')->nullable(); // № свідоцтва/рішення/патенту
            $table->string('applicant')->nullable(); // Заявник
            $table->string('date_application')->nullable(); // Дата подачі
            $table->string('date_publication')->nullable(); // Дата публікації
            $table->boolean('commerc_university')->nullable(); // Комерціалізовано університетом
            $table->boolean('commerc_employees')->nullable(); // Комерціалізовано штатними співробітниками університету
            $table->boolean('access_mode')->nullable(); // Режим доступу
            $table->string('mpk')->nullable(); // МПК
            $table->string('application_number')->nullable(); // № заявки
            $table->integer('newsletter_number')->nullable(); // № бюлетеня
            $table->string('name_conference')->nullable(); // Назва конференції
            $table->string('url')->nullable(); // url електронне видання
            $table->text('out_data')->nullable(); // Вихідні дані
            $table->string('doi')->nullable();
            $table->boolean('nature_index')->nullable(); // обліковується рейтингом Natire Index
            $table->string('nature_science')->nullable(); // обліковується рейтингом Natire Scuence
            $table->boolean('db_scopus_percent')->nullable(); // до 10% за БД Scpous
            $table->boolean('db_wos_percent')->nullable(); // до 1% за БД WoS
            $table->boolean('cited_international_patents')->nullable(); // процитовані у міжнародних патентах
            $table->foreignId('add_user_id')->nullable(); // Користувач, що створив публікацію
            $table->foreignId('edit_user_id')->nullable(); // Користувач, що робив останнє редагування публікації
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

        Schema::table('publications', function (Blueprint $table) {
            $table->index('add_user_id');
            $table->foreign('add_user_id')->references('id')->on('authors')->onDelete('cascade');
            $table->index('edit_user_id');
            $table->foreign('edit_user_id')->references('id')->on('authors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
