<?php

namespace App\Modules\Product\Controllers\Api\Front\ProductCollection;

use App\Http\Controllers\Controller;
use App\Modules\Product\Helpers\ProductCollectionHelper;
use App\Modules\Product\Resources\MinimalProductCollection;
use App\Modules\Product\Resources\ProductCollection\MinimalProductCollectionResource;
use App\Modules\Product\Resources\ProductCollection\ProductCollectionWithLimitedProductsResource;
use App\Modules\Product\Services\ProductCollection\ProductCollectionService;
use App\Modules\Product\Services\ProductPriceService;
use Exception;

class ProductCollectionController extends Controller
{
    protected $productCollectionService;

    public function __construct(ProductCollectionService $productCollectionService)
    {
        $this->productCollectionService = $productCollectionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllActiveProductCollections()
    {
        try {
            //$collectionWithProducts = $this->productCollectionService->getActiveProductCollections();
            $collectionWithProducts = ProductCollectionHelper::getActiveProductCollectionsWithActiveProducts();

//            return $collectionWithProducts;
            $collectionWithProducts = ProductCollectionWithLimitedProductsResource::collection($collectionWithProducts);
            return sendSuccessResponse('Data Found', $collectionWithProducts);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }


    public function getProductCollectionDetails($slug)
    {

        try {
            $collection = $this->productCollectionService->findOrFailProductCollectionBySlug($slug);
            $collection = new MinimalProductCollectionResource($collection);
            return sendSuccessResponse('Data Found', $collection);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

    public function getProductsOfCollection($slug)
    {
        try {
            $collection = $this->productCollectionService->findOrFailProductCollectionBySlug($slug);
            $products = $this->productCollectionService->getProductsOfCollectionWithPagination($collection);
            return  new MinimalProductCollection($products);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

}
