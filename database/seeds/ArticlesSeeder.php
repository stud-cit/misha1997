<?php

use Illuminate\Database\Seeder;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->insert([
            'title' => 'Test',
            'year' => 2020,
            'number' => 1,
            'pages' => 100,
            'country' => 'Україна',
            'doi' => 'test',
            'publications_id' => 1
        ]);
    }
}
