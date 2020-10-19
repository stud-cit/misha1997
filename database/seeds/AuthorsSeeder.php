<?php

use Illuminate\Database\Seeder;

class AuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authors')->insert([
            'guid' => '22e6106c-c580-e711-8194-001a4be6d04a',
            'job' => 'СумДУ',
            'name' => 'Admin',
            'roles_id' => 4
        ]);
    }
}
