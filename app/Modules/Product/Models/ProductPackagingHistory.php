<?php


namespace App\Modules\Product\Models;


use App\Modules\Package\Models\PackageType;
use Illuminate\Database\Eloquent\Model;

class ProductPackagingHistory extends Model
{

    protected $table = 'product_packaging_history';
    protected $primaryKey = 'product_packaging_history_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'product_packaging_history_code',
        'product_code',
        'product_variant_code',
        'micro_unit_code',
        'unit_code',
        'macro_unit_code',
        'super_unit_code',
        'micro_to_unit_value',
        'unit_to_macro_value',
        'macro_to_super_value',
        'created_by',
        'updated_by',
        'from_date',
        'to_date'
    ];


    public function getMicroToUnitValueAttribute($value){
        return is_null($value) ? null : (int) $value;
    }
    public function getUnitToMacroValueAttribute($value){
        return is_null($value) ? null : (int) $value;
    }
    public function getMacroToSuperValueAttribute($value){
        return is_null($value) ? null : (int) $value;
    }


    public function generateCode()
    {
        $prefix = 'PPH';
        $initialIndex = '1000';
        $packageDetail = self::latest('id')->first();
        if($packageDetail){
            $codeTobePad = (int) (str_replace($prefix,"",$packageDetail->product_packaging_history_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->product_packaging_history_code = $model->generateCode();
        });

    }

    public function microPackageType(){
        return $this->belongsTo(PackageType::class,'micro_unit_code','package_code');
    }

    public function unitPackageType(){
        return $this->belongsTo(PackageType::class,'unit_code','package_code');
    }

    public function macroPackageType(){
        return $this->belongsTo(PackageType::class,'macro_unit_code','package_code');
    }

    public function superPackageType(){
        return $this->belongsTo(PackageType::class,'super_unit_code','package_code');
    }

    public function product(){
        return $this->belongsTo(ProductMaster::class,'product_code','product_code');
    }
    public function productVariant(){
        return $this->belongsTo(ProductVariant::class,'product_variant_code','product_variant_code');
    }
}
