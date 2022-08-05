<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductPriceList;
use App\Modules\Product\Repositories\ProductPriceRepository;
use App\Modules\Product\Repositories\ProductRepository;
use Exception;

class ProductPriceService
{
    private $productPriceRepository,$productRepository;
    public function __construct(ProductPriceRepository $productPriceRepository,ProductRepository $productRepository)
    {
        $this->productPriceRepository = $productPriceRepository;
        $this->productRepository = $productRepository;
    }

    


    private function getPercentageFlatValue(ProductPriceList $productPriceList,$mrp){

       /* $adminMarginCost = $this->calculatePercentageFlatValue($productPriceList->admin_margin_type,
            $productPriceList->admin_margin_value,$mrp);*/

        $wholesaleMarginCost = $this->calculatePercentageFlatValue($productPriceList->wholesale_margin_type,
            $productPriceList->wholesale_margin_value,$mrp);

        $retailMarginCost = $this->calculatePercentageFlatValue($productPriceList->retail_store_margin_type,
            $productPriceList->retail_store_margin_value,$mrp);

        return [
            //'adminMarginCost' =>$adminMarginCost,
            'wholesaleMarginCost' => $wholesaleMarginCost,
            'retailMarginCost' => $retailMarginCost
        ];
    }

    private function calculatePercentageFlatValue($marginType,$marginValue,$mrp){

        if ($marginType == 'p'){

            $marginCost= ($marginValue/100 )*$mrp;
        }
        else{
            $marginCost =$marginValue;
        }

        return $marginCost;
    }


    private function getCalculatedProductPrice($productPriceLists){

        $priceRanges=[];

        foreach ($productPriceLists as $productPriceList){

            $mrp = $productPriceList->mrp;

            $marginCost = $this->getPercentageFlatValue($productPriceList,$mrp);

            //$adminMarginCost = $marginCost['adminMarginCost'];
            $wholesaleMarginCost = $marginCost['wholesaleMarginCost'];
            $retailMarginCost = $marginCost['retailMarginCost'];

            $totalCost = $mrp-$wholesaleMarginCost-$retailMarginCost;

            array_push($priceRanges,$totalCost);

        }

        return $priceRanges;
    }

    public function getProductPriceList(ProductMaster $product){

        if (count($product->productVariants) > 0){
            $productVariantsCode = $product->productVariants()->pluck('product_variant_code')->toArray();

            $prices = $this->productPriceRepository->getPriceHavingVariants($product->product_code,$productVariantsCode);

            if (!$prices){
                throw new Exception('Price list not found for the product');
            }

            $prices = $this->getCalculatedProductPrice($prices);
        }
        else{
            $price = $this->productPriceRepository->getPriceNotHavingVariants($product->product_code);
            $prices = $this->getCalculatedProductPrice(collect([$price]));

        }

        return $prices;
    }

    public function getProductPriceByProductCode($productCode){

        try{

            $product = $this->productRepository->findOrFailProductByCodeWith($productCode,['productVariants']);

            return $this->getProductPriceList($product);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function getPriceOrRangeOfProduct(ProductMaster $productMaster){

        try{

            $prices = $this->getProductPriceList($productMaster);
            if (count($prices) > 1){
                return [
                    'min' => min($prices),
                    'max' => max($prices),
                ];
            }

            return $prices;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function getProductVariantPrice($productCode,$variantCode){

        $price = $this->productPriceRepository->findOrFailByProductCodeAndVariantCode($productCode,$variantCode);

        $prices = $this->getCalculatedProductPrice(collect([$price]));

        return $prices;
    }

    public function getProductPrice($productCode,$variantCode=null){

        if ($variantCode){
            $price = $this->productPriceRepository->findOrFailByProductCodeAndVariantCode($productCode,$variantCode);

        }
        else{
            $price = $this->productPriceRepository->getPriceNotHavingVariants($productCode);
        }

        $prices = $this->getCalculatedProductPrice(collect([$price]));

        return $prices[0];
    }


}