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
            AutorsAliasesSeeder::class,
            NotificationsSeeder::class,
            ScienceTypeSeeder::class,
            PublicationsSeeder::class,
            AuthorsPublicationsSeeder::class,
            ArticlesSeeder::class,
            RatingIndicatorsSeeder::class,
            PatentsSeeder::class
        ]);
    }
}
