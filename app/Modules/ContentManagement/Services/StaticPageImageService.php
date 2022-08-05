<?php

namespace App\Modules\ContentManagement\Services;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\ContentManagement\Models\StaticPageImage;
use App\Modules\ContentManagement\Repositories\StaticPageImageRepository;
use Exception;
use Illuminate\Support\Facades\DB;


class StaticPageImageService
{
    use ImageService;

    private $staticPageImageRepository;

    public function __construct(StaticPageImageRepository $staticPageImageRepository)
    {
        $this->staticPageImageRepository = $staticPageImageRepository;
    }

    public function getAllSitePagesImageByGroupBy()
    {
        return $this->staticPageImageRepository->getAllSitePagesImageByGroupBy();
    }

    public function getAllImagesOfStaticPageByPageName($page_name)
    {
        return $this->staticPageImageRepository->getAllImagesOfStaticPageByPageName($page_name);
    }

    public function storeStaticPageImage($validatedData)
    {
        DB::beginTransaction();

        $filename = '';
        try{
            $image = $validatedData['image'];
            $filename = $this->storeImageInServer($image, StaticPageImage::DOCUMENT_PATH);
            $validatedData['image'] = $filename;
            $staticPageImage = $this->staticPageImageRepository->store($validatedData);

            DB::commit();
            return $staticPageImage;

        }catch (Exception $exception){
            $this->deleteImageFromServer(StaticPageImage::DOCUMENT_PATH,$filename);
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteStaticPageImage($page_name)
    {

        DB::beginTransaction();
        try{
            $staticPageImageData = $this->staticPageImageRepository->getAllImagesOfStaticPageByPageName($page_name);
            foreach($staticPageImageData as $key => $value){
                $filename = $value['image'];
                $this->deleteImageFromServer(StaticPageImage::DOCUMENT_PATH,$filename);
                $staticPageImage = $this->staticPageImageRepository->delete($value->static_page_image_code);
            }
            DB::commit();
            return $staticPageImage;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteStaticPageImageSingleImage($SPICode)
    {
        DB::beginTransaction();
        try{
            $staticPageImageData = $this->staticPageImageRepository->findorFail($SPICode);
                $filename = $staticPageImageData['image'];
                $this->deleteImageFromServer(StaticPageImage::DOCUMENT_PATH,$filename);
                $staticPageImageData = $this->staticPageImageRepository->delete($SPICode);
            DB::commit();
            return $staticPageImageData;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function findorFailForUpdate($SPICode)
    {
        try{
            $staticPageImageData = $this->staticPageImageRepository->findorFail($SPICode);
            return $staticPageImageData;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateStaticPageImage($validated,$SPICode)
    {

        DB::beginTransaction();

        try{

            $filename = '';
            $newFilename ='';

            $staticPageImageData = $this->staticPageImageRepository->findorFail($SPICode);
            $filename = $staticPageImageData['image'];

            if(isset($validated['image']) && file_exists($validated['image'])) {
                $this->deleteImageFromServer(StaticPageImage::DOCUMENT_PATH,$filename);
                $image = $validated['image'];
                $newFilename = $this->storeImageInServer($image, StaticPageImage::DOCUMENT_PATH);
                $validated['image'] = $newFilename;
            }

            $staticPageImageData = $this->staticPageImageRepository->update($staticPageImageData,$validated);

            DB::commit();
            return $staticPageImageData;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }



}
