<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the AdminSeeder which seeds Admin, Seller, and Buyer roles
        $this->call([
            AdminSeeder::class,
        ]);

        // Check if old test user already exists
        if (!Users::where('email', 'test@example.com')->exists()) {
            Users::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '081234567890',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Perorangan',
                'role' => 'buyer',
                'password' => bcrypt('password'),
            ]);
        }
    }
}
