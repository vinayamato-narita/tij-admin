<?php
namespace App\Components;


use App\Enums\AzureFolderEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TIJAdminAzureComponent
{

    private static function checkBlobExist($folderName, $blobName) : bool
    {
        return Storage::disk('azure')->exists( $folderName . '/' . $blobName);
    }

    private static function generateBlobName()
    {
        return Carbon::now()->timestamp;
    }

    public static function upload($folderName,UploadedFile $file)
    {
        do {
            $name = self::generateBlobName();
        }
        while (self::checkBlobExist($folderName, $name));
        $destinationName = $folderName . '/' . $name;
        if (Storage::disk('azure')->put($destinationName, $file->get())) {
            return $name;
        }
        return null;
    }


}
