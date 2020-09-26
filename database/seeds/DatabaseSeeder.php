<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            AuthorsSeeder::class,
            ScienceTypeSeeder::class,
            PublicationTypeSeeder::class,
            PublicationsSeeder::class,
            AuthorsPublicationsSeeder::class,
            CountriesSeeder::class,
            ServiceSeeder::class
        ]);
    }
}
