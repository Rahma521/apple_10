<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'super_admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('12345678'),
        ]);
      //  $admin->addRole('super_admin');

        $admin2 = Admin::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);
      //  $admin2->addRole('human_resource');
    }
}
