<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MediaStorage
{
    private ?VercelBlobClient $blobClient = null;

    public function driver(): string
    {
        $configured = config('media.disk', 'auto');

        if ($configured === 'public') {
            return 'public';
        }

        if ($configured === 'vercel-blob' || VercelBlobAuth::isVercelRuntime()) {
            return 'vercel-blob';
        }

        if ($configured === 'auto' && $this->blob()->hasCredentials()) {
            return 'vercel-blob';
        }

        return 'public';
    }

    public function usesBlob(): bool
    {
        return $this->driver() === 'vercel-blob';
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
            $result = $this->blob()->put(
                $path,
                file_get_contents($file->getRealPath()),
                [
                    'contentType' => $file->getMimeType() ?: 'application/octet-stream',
                    'access' => 'public',
                ]
            );

            return $result['url'];
        }

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $stored = $file->storeAs($directory, $filename, 'public');

        if (!$stored) {
            throw new RuntimeException('Gagal mengupload file ke storage lokal.');
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
                $this->blob()->delete([$pathOrUrl]);

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
                $this->blob()->head($pathOrUrl);

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

    private function blob(): VercelBlobClient
    {
        if ($this->blobClient === null) {
            $this->blobClient = app(VercelBlobClient::class);
        }

        return $this->blobClient;
    }
}
