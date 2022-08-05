<?php
namespace App\Modules\Product\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\Product\Models\ProductVariant;

class ProductVariantNameSeeder extends Seeder
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

            $productVariants = ProductVariant::with('details','details.variantValue')->get();

            foreach($productVariants as $productVariant){

                  $variantValuesNames = $productVariant->details->pluck('variantValue.variant_value_name')->toArray();
                  $newStrLowerValues = array_map('strtolower',$variantValuesNames);
                  $variantValueName = implode('-',$newStrLowerValues);

                  //ProductVariant::where('product_variant_code',$productVariant->product_variant_code)->update(['product_variant_name'=>$variantValueName]);
                   $productVariant->update(['product_variant_name'=>$variantValueName]);

                   echo "\033[32m".'Product Variant Name generated sucessfully Variant Code : '.$productVariant->product_variant_code.''."\n";
            }

            //dd($productVariants);


            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
