<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            RegionSeeder::class,
            CitySeeder::class,
            InstructorTypeSeeder::class,
            EducationLevelSeeder::class,
        ]);
    }
}
