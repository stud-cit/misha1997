<?php

use Illuminate\Database\Seeder;

class PublicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('publications')->insert([
            'title' => 'Test',
            'science_type_id' => 1,
            'publication_type_id' => 1,
            'impact_factor' => 'test',
            'sub_db_index' => 1,
            'scie' => 'test',
            'ssci' => 'test',
            'nature_index' => '11',
            'nature_scince' => '11',
            'other_organizations' => 'test',
            'forbes_fortune' => 'test',
            'date' => '2020-04-27',
            'international_patents' => 'test',
            'snip' => 'test'
        ]);
    }
}
