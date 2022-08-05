<?php

namespace App\Modules\Types\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorType extends Model
{
    use SoftDeletes;
    
    protected $table = 'vendor_types';
    protected $primaryKey = 'vendor_type_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'vendor_type_code',
        'vendor_type_name',
        'slug',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public static function generateVendorTypeCode()
    {
        $vendorTypePrefix = 'VT';
        $initialIndex = '001';
        $vendorType = self::withTrashed()->latest('id')->first();
        if($vendorType){
            $codeTobePad = str_replace($vendorTypePrefix,"",$vendorType->vendor_type_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestVendorTypeCode = $vendorTypePrefix.$paddedCode;
        }else{
            $latestVendorTypeCode = $vendorTypePrefix.$initialIndex;
        }
        return $latestVendorTypeCode;
    }
}
