<?php

namespace App\Modules\Brand\Models;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Category\Models\CategoryMaster;
use App\Modules\Product\Models\ProductMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes,CheckDelete;

    protected $table = 'brands';

    protected $primaryKey = 'brand_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'brand_name',
        'slug',
        'brand_code',
        'is_featured',
        'brand_logo',
        'remarks',
    ];

    CONST IMAGE_PATH = 'uploads/brand/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->brand_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function generateCode()
    {
        $prefix = 'B';
        $initialIndex = '1000';
        $brand= self::withTrashed()->latest('id')->first();
        if($brand){
            $codeTobePad = str_replace($prefix,"",$brand->brand_code) +1 ;
           // $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }


    public function categories(){
        return $this->belongsToMany(CategoryMaster::class,'category_brand','brand_code','category_code');
    }


    public function products(){
        return $this->hasMany(ProductMaster::class,'brand_code');
    }
    public function brandSliders(){
        return $this->hasMany(BrandSlider::class,'brand_code');
    }
    public function brandFollowers(){
        return $this->hasMany(BrandFollowersByStore::class,'brand_code');
    }
    public function productsCount(){
        return $this->hasManyThrough(WarehouseProductMaster::class,ProductMaster::class,
            'brand_code',
            'product_code','brand_code','product_code'
        );
    }

}
