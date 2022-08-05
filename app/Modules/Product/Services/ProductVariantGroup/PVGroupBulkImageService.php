<?php


namespace App\Modules\Product\Services\ProductVariantGroup;


use App\Modules\Product\Models\PVGroupBulkImage;
use App\Modules\Product\Repositories\ProductVariantGroup\PVGroupBulkImageRepository;

class PVGroupBulkImageService
{

    private $pvGroupBulkImageRepository;

    public function __construct(
        PVGroupBulkImageRepository $pvGroupBulkImageRepository
    )
    {
        $this->pvGroupBulkImageRepository = $pvGroupBulkImageRepository;
    }

    public function getGroupBulkImagesByGroupCode($productVariantGroupCode){
        return  $this->pvGroupBulkImageRepository->getGroupBulkImagesByGroupCode($productVariantGroupCode);
    }

    public function concatImagePath($productVariantBulkImages){

        $images=[];
        foreach ($productVariantBulkImages as $variantImage){
            array_push($images, url(PVGroupBulkImage::IMAGE_PATH.$variantImage->image));
        }
        return $images;
    }

}

