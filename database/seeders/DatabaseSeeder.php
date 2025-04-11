<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SportTypesTableSeeder::class,
            UsersTableSeeder::class,
            VenuesTableSeeder::class,
            FieldsTableSeeder::class,
            FieldOpeningHoursTableSeeder::class,
            ReviewsTableSeeder::class,
        ]);
    }
}
