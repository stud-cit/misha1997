<?php

use Illuminate\Database\Seeder;

class AuthorsPublicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authors_has_publications')->insert([
            'autors_id' => 1,
            'publications_id' => 1
        ]);
        DB::table('authors_has_publications')->insert([
            'autors_id' => 1,
            'publications_id' => 2
        ]);
    }
}
