<?php

namespace Database\Seeders;


use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EducationLevel::create([
            'name' => [
                'en' => 'Higher Education (University)',
                'ar' => 'تعليم عالي'
            ],
        ]);
    }
}
