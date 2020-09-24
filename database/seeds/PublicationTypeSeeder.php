<?php

use Illuminate\Database\Seeder;

class PublicationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('publication_type')->insert([
            'title' => 'Стаття у фахових виданнях України',
            'type' => 'article',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Стаття-доповідь у матеріалах наукових конференціях',
            'type' => 'article',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Інші статті',
            'type' => 'article',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Книга',
            'type' => 'book',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Посібник',
            'type' => 'book',
            'scopus_wos' => 0
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Монографія',
            'type' => 'book',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Розділ монографії',
            'type' => 'book-part',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Розділ книги',
            'type' => 'book-part',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Тези доповіді',
            'type' => 'thesis',
            'scopus_wos' => 1
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Патент',
            'type' => 'patent',
            'scopus_wos' => 0
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Свідоцтво про реєстрації авторських прав на твір/рішення',
            'type' => 'certificate',
            'scopus_wos' => 0
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Методичні вказівки / конспекти лекцій',
            'type' => 'methodical',
            'scopus_wos' => 0
        ]);
        DB::table('publication_type')->insert([
            'title' => 'Електронні видання',
            'type' => 'electronic',
            'scopus_wos' => 0
        ]);
    }
}
