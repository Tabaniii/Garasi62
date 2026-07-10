<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SanitizeProject extends Command
{
    protected $signature = 'project:sanitize {--apply}';
    protected $description = 'Sanitasi repository: hapus file sampah dan template yang tidak digunakan';

    public function handle()
    {
        $apply = (bool) $this->option('apply');
        $base = base_path();

        $targets = [];

        $zipFiles = glob($base . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '*.zip') ?: [];
        foreach ($zipFiles as $f) {
            $targets[] = $f;
        }

        $sqlFiles = glob($base . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . '*.sql') ?: [];
        foreach ($sqlFiles as $f) {
            if (str_contains($f, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR)) {
                continue;
            }
            $targets[] = $f;
        }

        $env2 = $base . DIRECTORY_SEPARATOR . '.env2';
        if (File::exists($env2)) {
            $targets[] = $env2;
        }

        $publicGarasi = $base . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'garasi62';
        if (File::isDirectory($publicGarasi)) {
            $htmls = glob($publicGarasi . DIRECTORY_SEPARATOR . '*.html') ?: [];
            foreach ($htmls as $f) {
                $targets[] = $f;
            }
            $sassDir = $publicGarasi . DIRECTORY_SEPARATOR . 'sass';
            if (File::isDirectory($sassDir)) {
                $iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sassDir, \FilesystemIterator::SKIP_DOTS));
                foreach ($iter as $file) {
                    if ($file->isFile()) {
                        $targets[] = $file->getPathname();
                    }
                }
                $targets[] = $sassDir;
            }
        }

        $iterAll = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterAll as $file) {
            if ($file->isFile()) {
                $name = $file->getFilename();
                if ($name === '.DS_Store' || $name === 'Thumbs.db') {
                    $targets[] = $file->getPathname();
                }
            }
        }

        $targets = array_values(array_unique($targets));

        if (empty($targets)) {
            $this->info('Tidak ada file sampah ditemukan.');
            return 0;
        }

        $this->info('Target sanitasi: ' . count($targets) . ' item');
        foreach ($targets as $t) {
            $this->line($t);
        }

        if (!$apply) {
            $this->info('Dry-run selesai. Jalankan dengan --apply untuk menghapus.');
            return 0;
        }

        $deleted = 0;
        foreach ($targets as $t) {
            try {
                if (File::isDirectory($t)) {
                    File::deleteDirectory($t);
                } else {
                    File::delete($t);
                }
                $deleted++;
            } catch (\Throwable $e) {
                $this->error('Gagal menghapus: ' . $t . ' -> ' . $e->getMessage());
            }
        }

        $this->info('Sanitasi selesai. Terhapus: ' . $deleted . ' item');
        return 0;
    }
}

