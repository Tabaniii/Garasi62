<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Hapus semua user lama yang menggunakan domain email @garasi62.com
        $deletedCount = User::where('email', 'like', '%@garasi62.com')->delete();
        $this->command->info("Berhasil menghapus {$deletedCount} user lama dengan domain @garasi62.com.");

        // 2. Data user default dengan domain @ride62.com
        $defaultUsers = [
            [
                'name' => 'Admin Ride62',
                'email' => 'admin@ride62.com',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Dealer',
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Seller Ride62',
                'email' => 'seller@ride62.com',
                'password' => Hash::make('seller123'),
                'phone' => '081234567891',
                'gender' => 'Laki-laki',
                'city' => 'Surabaya',
                'institution' => 'Dealer',
                'role' => 'seller',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Buyer Ride62',
                'email' => 'buyer@ride62.com',
                'password' => Hash::make('buyer123'),
                'phone' => '081234567892',
                'gender' => 'Perempuan',
                'city' => 'Bandung',
                'institution' => 'Perorangan',
                'role' => 'buyer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test Ride62',
                'email' => 'test@ride62.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'gender' => 'Laki-laki',
                'city' => 'Semarang',
                'institution' => 'Perorangan',
                'role' => 'buyer',
                'email_verified_at' => now(),
            ],
        ];

        // 3. Proses insert atau update ke database
        foreach ($defaultUsers as $userData) {
            $user = User::where('email', $userData['email'])->first();
            if ($user) {
                $user->update($userData);
                $this->command->info("User berhasil di-update: {$userData['email']}");
            } else {
                User::create($userData);
                $this->command->info("User berhasil dibuat: {$userData['email']}");
            }
        }
    }
}
