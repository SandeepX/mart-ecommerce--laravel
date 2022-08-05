<?php

namespace App\Modules\Vendor\Models;

use App\Modules\Location\Models\LocationHierarchy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorWareHouse extends Model
{   
    use SoftDeletes;
    protected $table = 'vendors_warehouse';
    protected $primaryKey = 'vendor_warehouse_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'vendor_warehouse_location',
        'vendor_warehouse_name',
        'vendor_code',
        'landmark_name',
        'latitude',
        'longitude',
        'remarks',
    ];

    public static function generateVendorWarehouseCode()
    {
        $vendorWarehousePrefix = 'VW';
        $initialIndex = '1000';
        $vendorWarehouse = self::withTrashed()->latest('id')->first();
        if($vendorWarehouse){
            $codeTobePad = (int)(str_replace($vendorWarehousePrefix,"",$vendorWarehouse->vendor_warehouse_code) +1) ;
           // $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestVendorWarehouseCode = $vendorWarehousePrefix.$codeTobePad;
        }else{
            $latestVendorWarehouseCode = $vendorWarehousePrefix.$initialIndex;
        }
        return $latestVendorWarehouseCode;
    }

    public function location(){
        return $this->belongsTo(LocationHierarchy::class, 'vendor_warehouse_location', 'location_code');
    }
}
