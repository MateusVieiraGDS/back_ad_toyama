<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait UploadTrait
{
    private function upload(UploadedFile $file, $path, $disk = 'local', $visibility = 'public'): string|false
    {
        $path = "{$this->folder()}/{$path}";
        if( $visibility === 'private') {
            return $file->store($path, $disk);
        }
        return $file->storePublicly($path, $disk);
    }

    private function deleteFile(string $path = null, $disk = 'local'): bool
    {
        if (empty($path) || $path == null) {
            return false;
        }
        if (Storage::disk($disk)->exists("{$this->folder()}/{$path}")) {
            Storage::disk($disk)->delete("{$this->folder()}/{$path}");
            return true;
        }
        return false;
    }

    private function folder()
    {
        return "folder_back_adtoyama";
    }
}