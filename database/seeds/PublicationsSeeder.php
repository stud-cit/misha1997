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
            'initials' => 'Test t. t.',
            'science_type_id' => 1,
            'publication_type_id' => 1,
            'impact_factor' => 'test',
            'sub_db_scie' => 0,
            'sub_db_ssci' => 0,
            'snip' => 'test'
        ]);
    }
}
