<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use VercelBlobPhp\Client;
use VercelBlobPhp\CommonCreateBlobOptions;

class MediaStorage
{
    private ?Client $client = null;

    public function usesBlob(): bool
    {
        $disk = config('media.disk', 'auto');

        if ($disk === 'vercel-blob') {
            return $this->token() !== '';
        }

        if ($disk === 'public') {
            return false;
        }

        return $this->token() !== '';
    }

    public function upload(UploadedFile $file, string $directory): string
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        return $this->uploadAs($file, $directory, $filename);
    }

    public function uploadAs(UploadedFile $file, string $directory, string $filename): string
    {
        if ($this->usesBlob()) {
            $path = trim($directory, '/') . '/' . $filename;
            $result = $this->client()->put(
                $path,
                file_get_contents($file->getRealPath()),
                new CommonCreateBlobOptions(
                    contentType: $file->getMimeType() ?: 'application/octet-stream',
                    addRandomSuffix: false,
                )
            );

            return $result->url;
        }

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $stored = $file->storeAs($directory, $filename, 'public');

        if (!$stored) {
            throw new \RuntimeException('Gagal mengupload file ke storage lokal.');
        }

        return $stored;
    }

    public function delete(?string $pathOrUrl): bool
    {
        if (empty($pathOrUrl)) {
            return false;
        }

        if ($this->isRemoteUrl($pathOrUrl)) {
            try {
                $this->client()->del([$pathOrUrl]);

                return true;
            } catch (\Throwable) {
                return false;
            }
        }

        if (Storage::disk('public')->exists($pathOrUrl)) {
            return Storage::disk('public')->delete($pathOrUrl);
        }

        return false;
    }

    public function exists(?string $pathOrUrl): bool
    {
        if (empty($pathOrUrl)) {
            return false;
        }

        if ($this->isRemoteUrl($pathOrUrl)) {
            try {
                $this->client()->head($pathOrUrl);

                return true;
            } catch (\Throwable) {
                return false;
            }
        }

        return Storage::disk('public')->exists($pathOrUrl);
    }

    public function url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if ($this->isRemoteUrl($path)) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'garasi62/') || str_starts_with($path, 'img/')) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    public function hashUploadedFile(UploadedFile $file): string
    {
        return hash_file('md5', $file->getRealPath());
    }

    public function hash(?string $pathOrUrl): ?string
    {
        if (empty($pathOrUrl)) {
            return null;
        }

        if ($this->isRemoteUrl($pathOrUrl)) {
            try {
                $content = @file_get_contents($pathOrUrl);

                return $content !== false ? md5($content) : null;
            } catch (\Throwable) {
                return null;
            }
        }

        $fullPath = Storage::disk('public')->path($pathOrUrl);

        if (file_exists($fullPath)) {
            return hash_file('md5', $fullPath);
        }

        return null;
    }

    public function findExistingImageByHash(string $fileHash, ?int $excludeCarId = null): ?string
    {
        $query = \App\Models\car::query()->whereNotNull('image');

        if ($excludeCarId) {
            $query->where('id', '!=', $excludeCarId);
        }

        foreach ($query->get() as $car) {
            foreach ($car->image ?? [] as $imagePath) {
                if ($this->hash($imagePath) === $fileHash) {
                    return $imagePath;
                }
            }
        }

        return null;
    }

    public function isRemoteUrl(string $path): bool
    {
        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }

    private function token(): string
    {
        return (string) (config('media.blob_token') ?: getenv('BLOB_READ_WRITE_TOKEN') ?: '');
    }

    private function client(): Client
    {
        if ($this->client === null) {
            $this->client = new Client($this->token() ?: null);
        }

        return $this->client;
    }
}
