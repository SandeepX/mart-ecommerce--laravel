<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWarranty extends Model
{
    use SoftDeletes,CheckDelete;
    protected $primaryKey = 'warranty_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [ 
        'warranty_code', 
        'warranty_name',
        'slug',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function generateWarrantyCode()
    {
        $warrantyPrefix = 'PW';
        $initialIndex = '0001';
        $warranty = self::withTrashed()->latest('id')->first();
        if($warranty){
            $codeTobePad = str_replace($warrantyPrefix,"",$warranty->warranty_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestWarrantyCode = $warrantyPrefix.$paddedCode;
        }else{
            $latestWarrantyCode = $warrantyPrefix.$initialIndex;
        }
        return $latestWarrantyCode;
    }


    public function productWarrantyDetails(){
      return $this->hasMany(ProductWarrantyDetail::class,'warranty_code');;
    }
}
