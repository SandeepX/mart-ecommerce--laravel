<?php


namespace App\Modules\Vendor\Services;

use App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder\WarehousePurchaseReturnRepository;
use App\Modules\Vendor\Helpers\VendorSalesOrderReturnFilter;
use Exception;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class VendorSalesReturnService
{

    private $warehousePurchaseReturnRepository;

    public function __construct(WarehousePurchaseReturnRepository $warehousePurchaseReturnRepository){

        $this->warehousePurchaseReturnRepository= $warehousePurchaseReturnRepository;
    }

    public function getAuthVendorSalesReturnDetail($warehousePurchaseOrderCode){

        try{
            $filterParameters=[
                'vendor_code' => getAuthVendorCode(),
                'warehouse_order_code' => $warehousePurchaseOrderCode
            ];
            $with =[
                'warehouseOrder.warehouse',
                'warehouseOrderDetail.product',
                'warehouseOrderDetail.productVariant',
            ];
            $salesReturnDetail = VendorSalesOrderReturnFilter::filterPaginatedVendorSalesOrderReturn(
                $filterParameters,10,$with);
            return $salesReturnDetail;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function respondToSalesReturnByVendor($validatedData,$warehousePurchaseReturnCode){
        try{
            $authVendorCode = getAuthVendorCode();
            $warehousePurchaseReturn = $this->warehousePurchaseReturnRepository->findOrFailPurchaseReturnByCode($warehousePurchaseReturnCode);
            if ($authVendorCode != $warehousePurchaseReturn->vendor_code){
                throw new ModelNotFoundException('Sales return not found');
            }
            if ($warehousePurchaseReturn->status != 'pending'){
                throw new Exception('Sales return already responded');
            }

            if($validatedData['status'] == 'rejected'){
                $validatedData['accepted_return_quantity'] =0;
            }
            else{
                if ($validatedData['accepted_return_quantity'] > $warehousePurchaseReturn->return_quantity){
                    throw new Exception('Accepted return quantity cannot be greater than return quantity');
                }
            }

            DB::beginTransaction();
            $this->warehousePurchaseReturnRepository->updateStatus($warehousePurchaseReturn,$validatedData);
            DB::commit();
           // return $storeMiscPayment;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
