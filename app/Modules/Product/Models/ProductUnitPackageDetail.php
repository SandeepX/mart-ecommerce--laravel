<?php


namespace App\Modules\Product\Models;


use App\Modules\Package\Models\PackageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnitPackageDetail extends Model
{
    use SoftDeletes;
    protected $table = 'product_packaging_details';
    protected $primaryKey = 'product_packaging_detail_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'product_packaging_detail_code',
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
        'deleted_by'
    ];

    const PACKAGING_UNIT_TYPES=[
      'micro_unit_code' =>'MICRO_UNIT_TYPE',
      'unit_code' =>'UNIT_TYPE',
      'macro_unit_code' =>'MACRO_UNIT_TYPE',
      'super_unit_code' =>'SUPER_UNIT_TYPE',
    ];

    const SUPER_PACKAGE_ORDER_VALUE =1;
    const MACRO_PACKAGE_ORDER_VALUE =2;
    const UNIT_PACKAGE_ORDER_VALUE =3;
    const MICRO_PACKAGE_ORDER_VALUE =4;

    public function generateCode()
    {
        $prefix = 'PPD';
        $initialIndex = '1000';
        $packageDetail = self::withTrashed()->latest('id')->first();
        if($packageDetail){
            $codeTobePad = (int) (str_replace($prefix,"",$packageDetail->product_packaging_detail_code) +1 );
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
            $model->product_packaging_detail_code = $model->generateCode();
            $model->created_by =getAuthUserCode();
            $model->updated_by =getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
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
    public function getMicroToUnitValueAttribute($value)
    {
        return (int)$value;
    }

    public function getUnitToMacroValueAttribute($value)
    {
        return (int)$value;
    }

    public function getMacroToSuperValueAttribute($value)
    {
        return (int)$value;
    }

    public static function determinePackagingBreakingLevel(array $productPackagingUnitsArr,$packageCode){
        $productPackagingUnitsArr = array_values(array_filter($productPackagingUnitsArr));

        return array_search($packageCode, $productPackagingUnitsArr)+1;
    }

}
