<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use App\Modules\Product\Models\ProductImage;

class RemoveDotExtensionSeeder extends Seeder
{

//    private $model;
//
//    public function __construct(ProductImage $productImage){
//        $this->model = $productImage;
//    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $date = date('Y_m_d_H_i_s');

        $noExtdir = public_path(ProductImage::IMAGE_PATH."completed_files_no_ext/");
        $withExtdir = public_path(ProductImage::IMAGE_PATH."completed_files_with_ext/");
        if(!file_exists($noExtdir)){
            mkdir($noExtdir);
        }
        if(!file_exists($withExtdir)){
            mkdir($withExtdir);
        }

        mkdir(public_path(ProductImage::IMAGE_PATH."completed_files_no_ext/".$date.""));
        mkdir(public_path(ProductImage::IMAGE_PATH."completed_files_with_ext/".$date.""));


        try{

            $brandsWithoutExtension = ProductImage::select
            (
                '*',
                DB::raw('SUBSTRING_INDEX(image,".",-1) as extension')
            )
                ->having('extension','')
                ->latest()
                ->get();

            $pathOfCompletedFilesNoExt = '/completed_files_no_ext/'.$date.'/';
            $pathOfCompletedFilesWithExt = '/completed_files_with_ext/'.$date.'/';
            $path = public_path(ProductImage::IMAGE_PATH);
            $nonExistsFilesData = [];
            $existsFilesData = [];


            DB::beginTransaction();

            foreach ($brandsWithoutExtension as $result){

                $noExtImageColValue = $result->image; //(bimal.)
                $noExtImageFileNameFullPath = $path.$noExtImageColValue; // fullpath
//            dd($noExtImageFileNameFullPath);
                if(file_exists($noExtImageFileNameFullPath)){

                    $imageMime = image_type_to_mime_type(exif_imagetype($noExtImageFileNameFullPath));
                    $extensionToSave = explode('/',$imageMime)[1];
                    $brandImageNameWithFullExtension = $result->image.$extensionToSave;
                    copy($noExtImageFileNameFullPath,$path.$brandImageNameWithFullExtension);  // copy file with extension in same folder
                    copy($noExtImageFileNameFullPath,$path.$pathOfCompletedFilesNoExt.$noExtImageColValue);  // copy file with extension in same folder
                    copy($path.$brandImageNameWithFullExtension,$path.$pathOfCompletedFilesWithExt.$brandImageNameWithFullExtension);  // copy file with extension in same folder
                    $result->update(['image'=> $brandImageNameWithFullExtension]);

                    array_push($existsFilesData,$result);

                }else{
                    array_push($nonExistsFilesData,$result);
                }
            }
            DB::commit();

            Storage::disk('public')->put('/product_images/completedfiles/'.$date.'.json', json_encode($existsFilesData));
            Storage::disk('public')->put('/product_images/noncompletedfiles/'.$date.'.json', json_encode($nonExistsFilesData));
            dd('Congratulations ! You have done it',$nonExistsFilesData);


        }catch (Exception $exception){

            DB::rollBack();
             dd($exception);
        }




    }
}
