<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Автор'
        ]);
        DB::table('roles')->insert([
            'name' => 'Модератор кафедрального рівня'
        ]);
        DB::table('roles')->insert([
            'name' => 'Модератор інститутського або факультетського рівня'
        ]);
        DB::table('roles')->insert([
            'name' => 'Адміністратор'
        ]);
    }
}
