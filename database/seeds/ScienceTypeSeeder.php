<?php

use Illuminate\Database\Seeder;

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
            'type' => 'Scopus, WoS'
        ]);
    }
}
