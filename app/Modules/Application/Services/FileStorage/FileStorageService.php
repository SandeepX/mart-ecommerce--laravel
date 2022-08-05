<?php


namespace App\Modules\Application\Services\FileStorage;

use Image;
use File;

class FileStorageService
{

    //save Image in server
    public static function saveFile($file,$filePath,bool $multiSize=false){


//        dd($file);
        if($file->isValid()){

            if ($multiSize){
                $fileNameToStore =self::saveMultiSizeImage($file,$filePath);
            }
            else{
                $fileNameToStore =self::saveFileToServer($file,$filePath);
            }

            return $fileNameToStore;
        }
        else{
            throw new \Exception('Invalid File');
        }

    }

    private static function makeFolderIfNotPresent($dirName){

        if(!is_dir($dirName)){
            //Directory does not exist, so lets create it.
            $isDirMade =mkdir($dirName, 755, true);

            if (!$isDirMade){

                throw new \Exception('Could Not Make Directory');
            }
        }
    }

    private static function saveFileToServer($file,$filePath){

        // self::makeFolderIfNotPresent($filePath);// checking if file directory exists
        $filenameToStore = self::createFileName($file);
        //$location = public_path('common/images/');
        $file->move($filePath, $filenameToStore);

        return $filenameToStore;
    }

    private static function saveMultiSizeImage($file,$filePath){

        $fileType =exif_imagetype($file);//determines type of image ..if not image..the return value is FALSE

        if ($fileType == false){
            throw new \Exception('File type must be an image');
        }

        $filenameToStore = self::createFileName($file);

        $smallImagePath =$filePath.'small/';
        $mediumImagePath =$filePath.'medium/';

        self::makeFolderIfNotPresent($filePath);
        self::makeFolderIfNotPresent($smallImagePath);
        self::makeFolderIfNotPresent($mediumImagePath);

        $imagePathWithFileName = $filePath.$filenameToStore;
        $smallImagePathWithFileName = $smallImagePath.$filenameToStore;
        $mediumImagePathWithFileName =  $mediumImagePath.$filenameToStore;

        // Resize Image Code
        //$file->move($filePath,$filenameToStore);
        Image::make($file->getRealPath())->save($imagePathWithFileName);//original image
        Image::make($file->getRealPath())->resize(80,80)->save($smallImagePathWithFileName);
        Image::make($file->getRealPath())->resize(450,450)->save($mediumImagePathWithFileName);

        return $filenameToStore;
    }

    private static function createFileName($file){


        //get filename with extension
        $filenameWithExt = $file->getClientOriginalName();

        //get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        //get extension
        $extension = $file->getClientOriginalExtension();
        $extension = $extension == "" ? explode('/',$file->getMimeType())[1] : $extension;

        // create new filename
        $filenameToStore = uniqueHash() . '_' . time() . '.' . $extension;


        return $filenameToStore;
    }

    public static function deleteFile($filePath,$toBeDeletedFile){

        $smallImagePath =$filePath.'small/';
        $mediumImagePath =$filePath.'medium/';

        if(file_exists($filePath.$toBeDeletedFile)) {
            File::delete($filePath.$toBeDeletedFile);
        }
        if(file_exists($smallImagePath.$toBeDeletedFile)) {
            File::delete($smallImagePath.$toBeDeletedFile);
        }
        if(file_exists($mediumImagePath.$toBeDeletedFile)) {
            File::delete($mediumImagePath.$toBeDeletedFile);
        }
    }

    //save Image With Original Name
    public static function saveFileWithOriginalName($file,$filePath,$fileNameToSave = NULL){
        if($file->isValid()){
            $fileNameToStore =self::saveFileWithOriginalNameInServer($file,$filePath,$fileNameToSave);
            return $fileNameToStore;
        }
        else{
            throw new \Exception('Invalid File');
        }
    }

    public static function saveFileWithOriginalNameInServer($file,$filePath,$fileNameToSave =NULL){
        $filenameToStore = self::createOriginalFileName($file,$fileNameToSave);
        $file->move($filePath, $filenameToStore);
        return $filenameToStore;
    }

    public static function createOriginalFileName($file,$fileNameToSave=NULL){
        //get filename with extension
        $filenameWithExt = $file->getClientOriginalName();
        //get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get extension
        $extension = $file->getClientOriginalExtension();
        $extension = $extension == "" ? explode('/',$file->getMimeType())[1] : $extension;

        // create new filename
        $filenameToStore = $filename.'.'.$extension;
        if($fileNameToSave){
            $filenameToStore = $fileNameToSave.'.'.$extension;
        }

        return $filenameToStore;
    }



}
