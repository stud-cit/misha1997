<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
