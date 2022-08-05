<?php

namespace App\Modules\AlpasalWarehouse\Services\Setting;


use App\Modules\Accounting\Helpers\FiscalYear\FiscalYearHelper;
use App\Modules\AlpasalWarehouse\Helpers\Setting\InvoiceSettingHelper;
use App\Modules\AlpasalWarehouse\Repositories\Setting\InvoiceSettingRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class InvoiceSettingService
{
    private $invoiceSettingRepository;

    public function __construct(InvoiceSettingRepository $invoiceSettingRepository){
        $this->invoiceSettingRepository = $invoiceSettingRepository;
    }

    public function getAllInvoiceSettings($with=[],$select='*'){
        return $this->invoiceSettingRepository->getAllInvoiceSettings($with,$select);
    }

    public function  storeInvoiceSettings($validatedData){

        try{
            $validatedData['warehouse_code'] = getAuthWarehouseCode();
            $validatedData['created_by'] = getAuthUserCode();
            $validatedData['updated_by'] = getAuthUserCode();
            $validatedData['fiscal_year_code'] = FiscalYearHelper::getCurrentFiscalYearCode();

            if(InvoiceSettingHelper::canCreateNextInvoiceSetting($validatedData['order_type'],$validatedData['fiscal_year_code'])){
                throw new Exception('Cannot Create Next Bundle Until One is Finished.');
            }

            if (InvoiceSettingHelper::isInvoiceSettingStartingandEndingNumberOverlapping(
                $validatedData['order_type'],
                $validatedData['starting_number'],
                $validatedData['ending_number'],
                $validatedData['fiscal_year_code']
            )){
                throw new Exception('Input StartingNumber and Ending Number overlaps ,please select other Number.');
            }


            $validatedData['next_number'] = $validatedData['starting_number'];

            DB::beginTransaction();
            $invoiceSetting = $this->invoiceSettingRepository->storeInvoiceSettings($validatedData);
            DB::commit();
            return $invoiceSetting;

        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }

    }

    public function findOrFailBySettingInvoiceCode($settingWarehouseInvoiceCode){

      return  $invoiceSettings = $this->invoiceSettingRepository->findOrFailBySettingInvoiceCode($settingWarehouseInvoiceCode);

    }
    public function updateInvoiveSettings($validatedData,$settingWarehouseInvoiceCode){

        try{
            $validatedData['updated_by'] = getAuthUserCode();

           $invoiceSettings = $this->invoiceSettingRepository->findOrFailBySettingInvoiceCode($settingWarehouseInvoiceCode);
            if (InvoiceSettingHelper::isInvoiceSettingStartingandEndingNumberOverlapping(
                $validatedData['order_type'],
                $validatedData['starting_number'],
                $validatedData['ending_number'],
                $invoiceSettings->fiscal_year_code,
                $settingWarehouseInvoiceCode
            )){
                throw new Exception('Input StartingNumber and Ending Number overlaps ,please select other Number.');
            }
            DB::beginTransaction();
             $invoiceSettings =  $this->invoiceSettingRepository->update($invoiceSettings,$validatedData);
            DB::commit();
            return $invoiceSettings;
         }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public  function  deleteWarehouseSettingInvoice($settingWarehouseInvoiceCode){
        try {
            $invoiceSetting =$this->invoiceSettingRepository->findOrFailBySettingInvoiceCode($settingWarehouseInvoiceCode);

            DB::beginTransaction();
            $this->invoiceSettingRepository->delete($invoiceSetting);
            DB::commit();
            return $invoiceSetting;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }

}
