<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    public function run()
    {
        if (!Users::where('email', 'admin@garasi62.com')->exists()) {
            Users::create([
                'name' => 'Administrator',
                'email' => 'admin@garasi62.com',
                'phone' => '08210008062',
                'gender' => 'Laki-laki',         
                'city' => 'Jakarta',
                'institution' => 'Dealer',
                'password' => Hash::make('admin123'),
            ]);
            
            $this->command->info('Akun admin berhasil dibuat!');
            $this->command->info('Email: admin@garasi62.com');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Akun admin sudah ada!');
        }
    }
}