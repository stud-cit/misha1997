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
            'guid' => 1,
            'job' => 'СумДУ',
            'country' => 'Україна',
            'h_index' => 1,
            'scopus_autor_id' => 1,
            'scopus_researcher_id' => 1,
            'orcid' => 'test',
            'department' => 'Кафедра X',
            'faculty' => 'Факультет Х',
            'is_student' => 0,
            'academic_code' => '000',
            'email' => 'test@gmail.com',
            'roles_id' => 1
        ]);
    }
}
