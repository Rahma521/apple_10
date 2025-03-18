<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::create([
            'name' => [
                'en' => 'Riyadh City',
                'ar' => 'مدينة الرياض'
            ],
            'region_id' => Region::first()->id
        ]);
    }
}
