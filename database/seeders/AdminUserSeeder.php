<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update atau create user admin
        $adminUser = Users::firstOrNew(['email' => 'admin@garasi62.com']);
        
        $adminUser->name = $adminUser->name ?? 'Administrator';
        $adminUser->email = 'admin@garasi62.com';
        $adminUser->password = Hash::make('admin123');
        $adminUser->phone = $adminUser->phone ?? '081234567890';
        $adminUser->gender = $adminUser->gender ?? 'Laki-laki';
        $adminUser->city = $adminUser->city ?? 'Jakarta';
        $adminUser->institution = $adminUser->institution ?? 'Dealer';
        $adminUser->role = 'admin';
        
        $adminUser->save();

        // Update semua user lain menjadi role user (kecuali admin)
        Users::where('email', '!=', 'admin@garasi62.com')
            ->update(['role' => 'user']);

        $this->command->info('Admin user berhasil dibuat/updated: admin@garasi62.com (password: admin123)');
        $this->command->info('Semua user lain telah di-set role menjadi user');
    }
}
