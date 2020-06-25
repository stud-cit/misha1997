<?php

use Illuminate\Database\Seeder;

class PatentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('patents')->insert([
            'number' => 12,
            'country' => 'Україна',
            'mpk' => 'test',
            'applicant' => 'Test T. T.',
            'application_number' => 1,
            'date_application' => '2020-04-27',
            'date_publication' => '2020-04-27',
            'newsletter_number' => 1,
            'commerc_university' => 0,
            'commerc_employees' => 0,
            'publications_id' => 2
        ]);
    }
}
