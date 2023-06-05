<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait{

    public function AddImage($FolderName = "Images", $SupFolderName = "Img" , $img)
    {
        $imagePath = time() . rand() . $SupFolderName .".". $img->extension();
        $path = Storage::putFileAs('public', $img, $FolderName.'/'.$SupFolderName.'/'.$imagePath);
        return Storage::url($path);
    }

    public function UpdateImage($FolderName = "Images", $SupFolderName = "Img" , $img , $oldimg)
    {
        if (!is_null($oldimg)){
            Storage::delete($oldimg);
            // unlink(storage_path($oldimg));
        }
        $imagePath = time() . rand() . $SupFolderName . '.'. $img->extension();
        $path = Storage::putFileAs('public', $img, $FolderName.'/'.$SupFolderName.'/'.$imagePath);
        return Storage::url($path);
    }


}
