<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\car;
use Illuminate\Support\Facades\Storage;

class CleanupUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cars:cleanup-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup unused car images from storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of unused images...');
        
        // Get all used images from database
        $usedImages = [];
        $cars = car::whereNotNull('image')->get();
        
        foreach ($cars as $car) {
            if ($car->image && is_array($car->image)) {
                $usedImages = array_merge($usedImages, $car->image);
            }
        }
        
        $usedImages = array_unique($usedImages);
        $this->info('Found ' . count($usedImages) . ' used images in database.');
        
        // Get all files in cars directory
        $allFiles = Storage::disk('public')->files('cars');
        $this->info('Found ' . count($allFiles) . ' files in storage.');
        
        $deletedCount = 0;
        $deletedSize = 0;
        
        foreach ($allFiles as $file) {
            if (!in_array($file, $usedImages)) {
                $size = Storage::disk('public')->size($file);
                Storage::disk('public')->delete($file);
                $deletedCount++;
                $deletedSize += $size;
                $this->line('Deleted: ' . basename($file));
            }
        }
        
        $deletedSizeMB = round($deletedSize / 1024 / 1024, 2);
        
        $this->info("Cleanup completed!");
        $this->info("Deleted {$deletedCount} unused images ({$deletedSizeMB} MB)");
        
        return 0;
    }
}
