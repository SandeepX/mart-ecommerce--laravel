<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Product\Models\ProductVariantDetail;

class ProductVVCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();

            $productVariants = ProductVariant::whereNull('product_vv_code')->get();

             if($productVariants){
                 foreach($productVariants as $productVariant){
                     $productVariantValues = ProductVariantDetail::where('product_variant_code',$productVariant->product_variant_code)
                                                                 ->pluck('variant_value_code')
                                                                 ->toArray();

                  $product_vv_code =  implode('-',$productVariantValues);
                  $productVariant->update(['product_vv_code'=>$product_vv_code]);
                  echo "\033[32m".'Product VV Code  made for Variant Code: '.$productVariant->product_variant_code."\n".'';

                 }
             }else{
                 echo "\033[31m".'No Variants Found To create product vv code'."\n".'';

             }

             echo "\033[34m".'Congratulations! Product VV code generated sucessfully',"\n";

            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();

            dd($exception);

        }
    }
}
