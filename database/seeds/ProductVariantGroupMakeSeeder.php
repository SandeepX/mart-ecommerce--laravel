<?php

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Product\Models\ProductVariantDetail;
use App\Modules\Product\Models\ProductVariantGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductVariantGroupMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{

            $products = ProductMaster::pluck('product_code')->toArray();
            $productInGroup = ProductVariantGroup::groupBy('product_code')->pluck('product_code')->toArray();

            $productsToMakeGroup = array_diff($products,$productInGroup);
            DB::beginTransaction();
            foreach($productsToMakeGroup as $productToMakeGroup){
                 //P1775
                //P1783
                $productVariants = ProductVariant::where('product_code',$productToMakeGroup)
                                                   ->get();
                $allGroupOfProductCode = [];

                if(count($productVariants)>0) {
                    foreach ($productVariants as $key => $productVariant) {
                        $productVariantDetails = ProductVariantDetail::with('variantValue.variant')->where('product_variant_code', $productVariant->product_variant_code);

                        if(count($productVariantDetails->get()) > 1){


                            $variantValues = $productVariantDetails->take(count($productVariantDetails->get())-1)->get()->pluck('variantValue.variant_value_code','variantValue.variant_value_name')->toArray();

                            $variantValueCodes = implode("-", $variantValues);
                            $groupName = implode('-',array_keys($variantValues));

                            array_push( $allGroupOfProductCode,[
                                'variantCode' => $productVariant->product_variant_code,
                                'groupName' => $groupName,
                                'variantValue' => $variantValueCodes
                            ]);

                        }else{

                            $variantDetails = $productVariantDetails->first();

                                array_push( $allGroupOfProductCode,[
                                    'variantCode' => $productVariant->product_variant_code,
                                    'groupName' => $variantDetails->variantValue->variant->variant_name,
                                    'variantValue' => $variantDetails->variantValue->variant->variant_code
                                ]);

                        }
                    }

                    $groupsToBeStored = array_group_by('groupName',$allGroupOfProductCode);

                    foreach($groupsToBeStored as $groupName => $groupToBeStored){
                        $data = [];
                        $data['product_code'] = $productToMakeGroup;
                        $data['group_name'] = $groupName;
                        $data['group_variant_value_code'] = $groupToBeStored[0]['variantValue'];
                        $groupDetail = ProductVariantGroup::create($data);

                        foreach($groupToBeStored as $value){
                            ProductVariant::where('product_variant_code',$value['variantCode'])
                                ->update(['product_variant_group_code'=>$groupDetail->product_variant_group_code]);

                            echo "\033[32m".'Group  made for Product Code: '.$productToMakeGroup.' Variant Code: '.$value['variantCode']."\n".'';

                        }
                    }


                }else{
                    echo "\033[31m".'Group Cannot be made for Product Code:'.$productToMakeGroup.' Because it does not have variants'."\n".'';
                }


            }

          //  dd('its end');
            echo "\033[34m".'Congratulation! Product group created successfully :)'."\n".'';

            DB::commit();

        }catch(Exception $exception){
            DB::rollBack();
            dd($exception);
        }

    }
}
