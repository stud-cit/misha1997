<?php

use Illuminate\Database\Seeder;

class PublicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('publication_status')->insert([
        'title' => 'Відкритий доступ'
      ]);
      DB::table('publication_status')->insert([
        'title' => 'В архіві'
      ]);
    }
}
