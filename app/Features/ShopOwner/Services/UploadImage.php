<?php

namespace App\Features\ShopOwner\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadImage
{
    public function uploadImage(
        UploadedFile $image,
        string $directory,
        string $disk = 'public'
    ): string {
        return $image->store($directory, $disk);
    }

    public function deleteImage(
        ?string $path,
        string $disk = 'public'
    ): void {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}