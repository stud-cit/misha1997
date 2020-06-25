<?php

use Illuminate\Database\Seeder;

class AutorsAliasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('autors_aliases')->insert([
            'surname_initials' => 'Test T. T.',
            'autors_id' => 1
        ]);
    }
}
