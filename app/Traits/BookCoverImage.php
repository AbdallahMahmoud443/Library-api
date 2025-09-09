<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait BookCoverImage
{
    //

    public function uploadCoverImage(UploadedFile $image): string
    {
        $customName = 'Book_Cover_' . Str::uuid() . '.' . $image->getClientOriginalExtension(); // generate custom name
        $path = $image->storeAs("/book_covers", $customName, 'public'); // upload image to public folder
        return "/uploads/" . $path;
    }

    public function deleteCoverImage($cover_image_path): void
    {
        File::delete(public_path($cover_image_path));
    }

    public function updateCoverImage(UploadedFile $image, $cover_image_path): string
    {
        if (!empty($cover_image_path)) {
            $this->deleteCoverImage($cover_image_path);
        }

        return $this->uploadCoverImage($image);
    }
}
