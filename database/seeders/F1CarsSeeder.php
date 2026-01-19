<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\car;
use App\Models\User;
use App\Models\CarApproval;
use Illuminate\Support\Facades\DB;

class F1CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get seller with email seller@garasi62.com
        $seller = User::where('email', 'seller@garasi62.com')->first();
        
        if (!$seller) {
            $this->command->error('Seller dengan email seller@garasi62.com tidak ditemukan!');
            $this->command->info('Silakan jalankan: php artisan db:seed --class=TestUsersSeeder');
            return;
        }
        
        // Get admin user for approval record
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = $seller; // Fallback jika tidak ada admin
        }

        $f1Cars = [
            [
                'brand' => 'Haas',
                'nama' => 'VF-24',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '25000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Jakarta, Indonesia',
                'description' => 'Haas VF-24 Formula 1 racing car dengan livery hitam, putih, dan merah. Mobil ini menampilkan sponsor MoneyGram, Chipotle, Palm Angels, dan MERCARI. Dilengkapi dengan teknologi F1 terbaru dan aerodinamika yang sangat canggih.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'Advanced Aerodynamics', 'DRS System', 'Energy Recovery System'],
                'image' => ['f1/haas-vf24-1.jpg', 'f1/haas-vf24-2.jpg'],
                'status' => 'approved',
                'vin' => 'HAAS-VF24-2024-001',
                'msrp' => '25000000',
            ],
            [
                'brand' => 'Alpine',
                'nama' => 'A524',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '28000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Bandung, Indonesia',
                'description' => 'Alpine A524 Formula 1 dengan livery hitam matte yang mencolok dengan aksen pink dan biru cerah. Mobil ini menampilkan sponsor utama BWT dan branding Alpine yang elegan. Desain aerodinamis yang sangat canggih untuk performa maksimal.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package with BWT branding',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'BWT Water Technology Integration', 'Advanced Aerodynamics', 'DRS System'],
                'image' => ['f1/alpine-a524-1.jpg', 'f1/alpine-a524-2.jpg'],
                'status' => 'approved',
                'vin' => 'ALPINE-A524-2024-001',
                'msrp' => '28000000',
            ],
            [
                'brand' => 'McLaren',
                'nama' => 'MCL38',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '32000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Surabaya, Indonesia',
                'description' => 'McLaren MCL38 Formula 1 dengan livery hitam yang mencolok dengan aksen orange dan biru muda. Mobil ini menampilkan sponsor VELO, Android, Richard Mille, Tezos, dan Darktrace. Desain yang sangat aerodinamis dengan teknologi F1 terdepan.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System', 'Richard Mille Timepiece Integration'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'VELO Branding', 'Android Integration', 'Advanced Aerodynamics', 'DRS System'],
                'image' => ['f1/mclaren-mcl38-1.jpg', 'f1/mclaren-mcl38-2.jpg'],
                'status' => 'approved',
                'vin' => 'MCLAREN-MCL38-2024-001',
                'msrp' => '32000000',
            ],
            [
                'brand' => 'Mercedes-AMG',
                'nama' => 'W15',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '35000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Jakarta, Indonesia',
                'description' => 'Mercedes-AMG Petronas W15 Formula 1 dengan livery hitam matte yang elegan dengan aksen teal/biru kehijauan. Mobil nomor 63 ini menampilkan sponsor Petronas, TeamViewer, AMD, CrowdStrike, IWC, dan INEOS. Teknologi F1 terdepan dengan performa maksimal.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package with Petronas technology',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System', 'IWC Timepiece Integration'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'Petronas Fuel Technology', 'TeamViewer Integration', 'Advanced Aerodynamics', 'DRS System'],
                'image' => ['f1/mercedes-w15-1.jpg', 'f1/mercedes-w15-2.jpg'],
                'status' => 'approved',
                'vin' => 'MERCEDES-W15-2024-063',
                'msrp' => '35000000',
            ],
            [
                'brand' => 'Stake F1',
                'nama' => 'C44',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '24000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Yogyakarta, Indonesia',
                'description' => 'Stake F1 Team C44 dengan livery hitam matte dan hijau neon yang sangat mencolok. Mobil ini menampilkan sponsor Stake, Kick, Sensetime.ai, Cielo, dan berbagai sponsor lainnya. Desain yang sangat agresif dan modern dengan teknologi F1 terkini.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'Stake Branding', 'Kick Integration', 'Advanced Aerodynamics', 'DRS System'],
                'image' => ['f1/stake-c44-1.jpg', 'f1/stake-c44-2.jpg'],
                'status' => 'approved',
                'vin' => 'STAKE-C44-2024-001',
                'msrp' => '24000000',
            ],
            [
                'brand' => 'Red Bull Racing',
                'nama' => 'RB20',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '38000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Jakarta, Indonesia',
                'description' => 'Red Bull Racing RB20 Formula 1 dengan livery biru gelap yang ikonik dengan aksen merah dan kuning. Mobil ini menampilkan sponsor Oracle, Mobil, AT&T, Honda, dan berbagai sponsor premium lainnya. Juara dunia dengan teknologi F1 terdepan.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid (Honda), Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System', 'Oracle Cloud Integration'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'Oracle Technology', 'Mobil 1 Lubricants', 'Honda Power Unit', 'Advanced Aerodynamics', 'DRS System'],
                'image' => ['f1/redbull-rb20-1.jpg', 'f1/redbull-rb20-2.jpg'],
                'status' => 'approved',
                'vin' => 'REDBULL-RB20-2024-001',
                'msrp' => '38000000',
            ],
            [
                'brand' => 'Aston Martin',
                'nama' => 'AMR24',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '30000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Bali, Indonesia',
                'description' => 'Aston Martin AMR24 Formula 1 dengan livery British Racing Green yang elegan dengan aksen kuning-hijau. Mobil ini menampilkan sponsor Aramco, BOSS, Cognizant, Pepperstone, dan berbagai sponsor premium. Kombinasi elegan antara tradisi Inggris dan teknologi F1 modern.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System', 'BOSS Branding'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'Aramco Technology', 'Cognizant Integration', 'Advanced Aerodynamics', 'DRS System'],
                'image' => ['f1/aston-martin-amr24-1.jpg', 'f1/aston-martin-amr24-2.jpg'],
                'status' => 'approved',
                'vin' => 'ASTON-AMR24-2024-001',
                'msrp' => '30000000',
            ],
            [
                'brand' => 'Williams',
                'nama' => 'FW46',
                'tahun' => '2024',
                'tipe' => 'buy',
                'harga' => '22000000',
                'kilometer' => '0',
                'transmisi' => 'Sequential',
                'kapasitasmesin' => '1.6L V6 Turbo Hybrid',
                'metode' => 'unit',
                'stock' => 'Tersedia',
                'location' => 'Medan, Indonesia',
                'description' => 'Williams FW46 Formula 1 dengan livery biru gelap yang klasik. Mobil nomor 55 ini menampilkan sponsor Atlassian, Komatsu, Gulf, MyProtein, Kraken, dan Duracell. Warisan balap yang kaya dengan teknologi F1 modern.',
                'technical_specs' => 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package',
                'interior_features' => ['F1 Racing Seat', 'Steering Wheel with Paddle Shifters', 'Halo Safety Device', 'Advanced Telemetry System'],
                'safety_features' => ['Halo Protection System', 'Monocoque Carbon Fiber Chassis', 'FIA Safety Standards', 'Fire Suppression System'],
                'extra_features' => ['Pirelli P Zero Tires', 'Atlassian Integration', 'Komatsu Technology', 'Gulf Branding', 'Advanced Aerodynamics', 'DRS System'],
                'image' => ['f1/williams-fw46-1.jpg', 'f1/williams-fw46-2.jpg'],
                'status' => 'approved',
                'vin' => 'WILLIAMS-FW46-2024-055',
                'msrp' => '22000000',
            ],
        ];

        foreach ($f1Cars as $carData) {
            $carData['seller_id'] = $seller->id;
            $car = car::create($carData);
            
            // Create approval record untuk konsistensi dengan sistem approval
            CarApproval::create([
                'car_id' => $car->id,
                'admin_id' => $admin->id,
                'action' => 'approved',
                'notes' => 'Dummy data F1 car - Auto approved by seeder',
                'approved_at' => now(),
            ]);
        }

        $this->command->info('8 Mobil F1 berhasil ditambahkan dengan seller: ' . $seller->email);
        $this->command->info('Semua mobil sudah disetujui dan siap ditampilkan di web!');
    }
}

