<?php
namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

trait ImageUploadTrait
{
    public function imageUpload(Request $request): array
    {
        $uploadedPaths = [];
        $imagesArray = $request->file('images');

        if(!$imagesArray){
            return $uploadedPaths;
        }

        $folderName = Str::slug($request->name);
        $destinationFolder = 'cards/' . $folderName;

        foreach($imagesArray as $image){
            if($image instanceof UploadedFile && $image->isValid()){
                    $path = $image->store($destinationFolder,'public');

                    $uploadedPaths[] = $path;
            }
        }

        return $uploadedPaths;
    }
}
