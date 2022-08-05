<?php


namespace App\Modules\Application\Traits\UploadImage;

use App\Modules\Application\Services\FileStorage\FileStorageService;

trait ImageService
{

    public function storeImageInServer($image,$imagePath,bool $multiSize=false){
        return FileStorageService::saveFile($image,$imagePath,$multiSize); //returns file name
    }

    public function deleteImageFromServer($imagePath,$toBeDeletedImage){

        FileStorageService::deleteFile($imagePath,$toBeDeletedImage); //returns file name
    }

    public function storeImageWithOriginalNameInServer($image,$imagePath,$fileName = NULL){
       return FileStorageService::saveFileWithOriginalName($image,$imagePath,$fileName); //returns file name
    }
}
