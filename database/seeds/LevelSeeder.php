<?php

use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('level_type')->insert([
            'title' => 'Студент'
        ]);
        DB::table('level_type')->insert([
            'title' => 'Аспірант'
        ]);
        DB::table('level_type')->insert([
            'title' => 'Докторант'
        ]);
        DB::table('level_type')->insert([
            'title' => 'Випускник'
        ]);
        DB::table('level_type')->insert([
            'title' => 'Співробітник'
        ]);
        DB::table('level_type')->insert([
            'title' => 'Викладач'
        ]);
        DB::table('level_type')->insert([
            'title' => 'Менеджер'
        ]);
    }
}
