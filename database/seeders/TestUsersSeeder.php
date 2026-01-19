<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Seller User (if not exists)
        if (!User::where('email', 'seller@garasi62.com')->exists()) {
            User::create([
                'name' => 'John Seller',
                'email' => 'seller@garasi62.com',
                'phone' => '081234567891',
                'gender' => 'Laki-laki',
                'city' => 'Surabaya',
                'password' => Hash::make('seller123'),
                'role' => 'seller',
            ]);
            $this->command->info('Seller user created!');
        } else {
            $this->command->info('Seller user already exists!');
        }

        $this->command->info('Test users setup completed!');
        $this->command->info('Admin: admin@garasi62.com (existing)');
        $this->command->info('Seller: seller@garasi62.com / seller123');
        $this->command->info('Buyer: user@gmail.com / (existing user)');
    }
}
