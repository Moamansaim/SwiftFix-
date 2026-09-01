<?php

namespace App\Features\ShopProduct\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageProduct
{
    /**
     * Upload an image.
     */
    public function upload(
        UploadedFile $image,
        string $directory = 'images'
    ): string {
        return $image->store($directory, 'public');
    }

    /**
     * Delete an image.
     */
    public function delete(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        if (! Storage::disk('public')->exists($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    /**
     * Replace an existing image with a new one.
     */
    public function replace(
        UploadedFile $image,
        ?string $oldPath,
        string $directory = 'images'
    ): string {
        $newPath = $this->upload($image, $directory);

        if ($oldPath) {
            $this->delete($oldPath);
        }

        return $newPath;
    }
}
