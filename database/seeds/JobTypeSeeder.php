<?php

use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_type')->insert([
            'title' => 'Учбовий заклад'
        ]);
        DB::table('job_type')->insert([
            'title' => 'Підприємство'
        ]);
        DB::table('job_type')->insert([
            'title' => 'Студент - випускник'
        ]);
        DB::table('job_type')->insert([
            'title' => 'Не працює'
        ]);
        DB::table('job_type')->insert([
            'title' => 'СумДУ'
        ]);
        DB::table('job_type')->insert([
            'title' => 'СумДУ (для співробітників, які працювали в звітному році та припинили свою діяльність)'
        ]);
    }
}
