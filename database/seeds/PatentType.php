<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatentType extends Seeder
{
    public function run()
    {
      DB::table('patent_type')->insert([
      'title' => 'Винахід'
      ]);
      DB::table('patent_type')->insert([
        'title' => 'Корисна модель'
      ]);
    }
}
