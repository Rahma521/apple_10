<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => [
                'en' => 'Apple',
                'ar' => ' آبل'
            ],
        ]);
    }
}
