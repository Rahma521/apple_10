<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => [
                'en' => 'phone',
                'ar' => ' موبايل'
            ],

            'brand_id' => Brand::first()->id
        ]);
        Category::create([
            'name' => [
                'en'=>'subcategory',
                'ar'=>'اقسام فرعية'
            ],

            'parent_id' => Category::first()->id
        ]);
    }
}
