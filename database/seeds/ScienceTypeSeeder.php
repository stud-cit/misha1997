<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScienceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('science_type')->insert([
            'type' => 'Scopus'
        ]);
        DB::table('science_type')->insert([
            'type' => 'WoS'
        ]);
        DB::table('science_type')->insert([
            'type' => 'Scopus та WoS'
        ]);
    }
}
