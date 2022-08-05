<?php

namespace App\Modules\Product\Models;

use App\Modules\Package\Models\PackageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPackageDetail extends Model
{
    use SoftDeletes;
    protected $table = 'product_package_details';
    protected $primaryKey = 'product_package_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'product_package_detail_code',
        'product_code',
        'package_code',
        'package_weight',
        'package_length',
        'package_height',
        'package_width',
        'units_per_package',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->product_package_detail_code = $model->generateProductPackageDetailCode();

        });
    }

    public static function generateProductPackageDetailCode()
    {
        $productPackageDetailPrefix = 'PV';
        $initialIndex = '1000';
        $productPackageDetail = self::withTrashed()->latest('id')->first();
        if($productPackageDetail){
            $codeTobePad = (int) (str_replace($productPackageDetailPrefix,"",$productPackageDetail->product_package_detail_code) +1 );
           // $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestProductPackageDetailCode = $productPackageDetailPrefix.$codeTobePad;
        }else{
            $latestProductPackageDetailCode = $productPackageDetailPrefix.$initialIndex;
        }
        return $latestProductPackageDetailCode;
    }

    public function packageType(){
        return $this->belongsTo(PackageType::class, 'package_code');
    }
}
