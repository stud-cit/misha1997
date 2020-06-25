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
            'type' => 'Стаття',
            'impact_factor' => 'test',
            'sub_db_index' => 1,
            'quartil' => 'test',
            'department_id' => 1,
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

        DB::table('publications')->insert([
            'title' => 'Test 2',
            'science_type_id' => 2,
            'type' => 'Стаття',
            'impact_factor' => 'test 2',
            'sub_db_index' => 1,
            'quartil' => 'test 2',
            'department_id' => 1,
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
