<?php

namespace App\Modules\AlpasalWarehouse\Repositories\Setting;

use App\Modules\AlpasalWarehouse\Models\Setting\InvoiceSetting;
use App\Modules\Application\Traits\UploadImage\ImageService;

class InvoiceSettingRepository
{
    use ImageService;
    public function getAllInvoiceSettings($with=[],$select='*'){
        return InvoiceSetting::with($with)->select($select)->latest()->get();
    }

    public  function storeInvoiceSettings($validatedData){
        //dd($validatedData);
        return InvoiceSetting::create($validatedData);
    }

    public function findOrFailBySettingInvoiceCode($settingWarehouseInvoiceCode){

        $invoiceSettings = InvoiceSetting::where('setting_warehouse_invoice_code',$settingWarehouseInvoiceCode)
           ->first();

        if(!$invoiceSettings){
             throw new \Exception('No Such Invoice Settings Found!');
        }
        return $invoiceSettings;
    }

    public function  update(InvoiceSetting $invoiceSettings,$validatedData){
         $invoiceSettings =   $invoiceSettings->update($validatedData);
        return $invoiceSettings;
    }

    public function delete(InvoiceSetting  $invoiceSettings){
        $invoiceSettings->delete();
        return $invoiceSettings;
    }

}

