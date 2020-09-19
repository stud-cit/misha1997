<?php

use Illuminate\Database\Seeder;

class AuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authors')->insert([
            'guid' => '22e6106c-c580-e711-8194-001a4be6d04a',
            'job' => 'СумДУ',
            'name' => 'Test Test Test',
            'country' => 'Україна',
            'h_index' => 1,
            'scopus_autor_id' => 1,
            'scopus_researcher_id' => 1,
            'orcid' => 'test',
            'department' => 'Кафедра X',
            'faculty' => 'Факультет Х',
            'academic_code' => '000',
            'email' => 'test@gmail.com',
            'roles_id' => 1
        ]);
    }
}
