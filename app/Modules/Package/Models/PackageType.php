<?php

namespace App\Modules\Package\Models;

use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Product\Models\ProductPackageDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageType extends Model
{
    use SoftDeletes,CheckDelete;
    protected $table = 'package_types';
    protected $primaryKey = 'package_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [ 
        'package_code', 
        'package_name',
        'slug',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function generatePackageCode()
    {
        $packagePrefix = 'PK';
        $initialIndex = '0001';
        $package = self::withTrashed()->latest('id')->first();
        if($package){
            $codeTobePad = str_replace($packagePrefix,"",$package->package_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestPackageCode = $packagePrefix.$paddedCode;
        }else{
            $latestPackageCode = $packagePrefix.$initialIndex;
        }
        return $latestPackageCode;
    }


    public function productPackageDetails(){
      return $this->hasMany(ProductPackageDetail::class,'package_code');
    }



}
