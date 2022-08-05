<?php
namespace  App\Modules\AlpasalWarehouse\Database\seeds;

use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductPackagingHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use Exception;

class WHOrderDetailsPackageQuantitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{

            $warehousePurchaseOrderDetails = PurchaseOrderDetail::orderBy('created_at','ASC')
                                                          ->get();
            DB::beginTransaction();
            foreach ($warehousePurchaseOrderDetails->chunk(1000) as $warehousePurchaseOrderDetails){
                foreach ($warehousePurchaseOrderDetails as $warehousePurchaseOrderDetail){

                   // dd($warehousePurchaseOrderDetail);
                    if($warehousePurchaseOrderDetail->package_code){
                        $productPackagingHistory = ProductPackagingHistory::where('product_packaging_history_code', $warehousePurchaseOrderDetail->product_packaging_history_code)
                                                                            ->firstOrFail();
                        $microQuantity =  ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                            $warehousePurchaseOrderDetail->package_code,
                            $productPackagingHistory,
                            $warehousePurchaseOrderDetail->quantity
                        );
                          $warehousePurchaseOrderDetail->update(
                              [
                                  'quantity' => $microQuantity,
                                  'package_quantity' => $warehousePurchaseOrderDetail->quantity
                              ]
                          );
                    }else{
                        $warehousePurchaseOrderDetail->update(
                            [
                                'quantity' => $warehousePurchaseOrderDetail->quantity,
                                'package_quantity' => 0
                            ]
                        );

                    }



                }
            }

            echo 'Seeder Completed Sucessfully';

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
