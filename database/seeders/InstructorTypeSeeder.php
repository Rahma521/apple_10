<?php

namespace Database\Seeders;

use App\Models\InstructorType;
use Illuminate\Database\Seeder;

class InstructorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InstructorType::create([
            'name' => [
                'en' => 'Educator',
                'ar' => 'مُعَلِّم'
            ]
        ]);
    }
}
