<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat/Update Admin
        $admin = Users::where('email', 'admin@garasi62.com')->first();
        
        if ($admin) {
            $admin->update([
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => Carbon::now(),
                'phone' => '081234567890',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Perorangan',
            ]);
            $this->command->info('Admin user updated successfully!');
        } else {
            Users::create([
                'name' => 'Administrator',
                'email' => 'admin@garasi62.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => Carbon::now(),
                'phone' => '081234567890',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Perorangan',
            ]);
            $this->command->info('Admin user created successfully!');
        }

        // Buat/Update Seller
        $seller = Users::where('email', 'seller@garasi62.com')->first();
        
        if ($seller) {
            $seller->update([
                'name' => 'Seller Account',
                'password' => Hash::make('seller123'),
                'role' => 'seller',
                'email_verified_at' => Carbon::now(),
                'phone' => '081234567891',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Dealer',
            ]);
            $this->command->info('Seller user updated successfully!');
        } else {
            Users::create([
                'name' => 'Seller Account',
                'email' => 'seller@garasi62.com',
                'password' => Hash::make('seller123'),
                'role' => 'seller',
                'email_verified_at' => Carbon::now(),
                'phone' => '081234567891',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Dealer',
            ]);
            $this->command->info('Seller user created successfully!');
        }

        // Buat/Update Buyer
        $buyer = Users::where('email', 'buyer@garasi62.com')->first();
        
        if ($buyer) {
            $buyer->update([
                'name' => 'Buyer Account',
                'password' => Hash::make('buyer123'),
                'role' => 'buyer',
                'email_verified_at' => Carbon::now(),
                'phone' => '081234567892',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Perorangan',
            ]);
            $this->command->info('Buyer user updated successfully!');
        } else {
            Users::create([
                'name' => 'Buyer Account',
                'email' => 'buyer@garasi62.com',
                'password' => Hash::make('buyer123'),
                'role' => 'buyer',
                'email_verified_at' => Carbon::now(),
                'phone' => '081234567892',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta',
                'institution' => 'Perorangan',
            ]);
            $this->command->info('Buyer user created successfully!');
        }
        
        $this->command->info('');
        $this->command->info('=== Account Credentials ===');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@garasi62.com');
        $this->command->info('  Password: admin123');
        $this->command->info('');
        $this->command->info('Seller:');
        $this->command->info('  Email: seller@garasi62.com');
        $this->command->info('  Password: seller123');
        $this->command->info('');
        $this->command->info('Buyer:');
        $this->command->info('  Email: buyer@garasi62.com');
        $this->command->info('  Password: buyer123');
    }
}
