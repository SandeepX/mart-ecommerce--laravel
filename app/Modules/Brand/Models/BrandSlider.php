<?php

namespace App\Modules\Brand\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandSlider extends Model
{
    use ModelCodeGenerator,SoftDeletes;
    protected $table = 'brand_sliders';
    protected $fillable = ['brand_code','image','description','is_active'];
    protected $primaryKey = 'brand_slider_code';
    public $incrementing = false;

    protected $keyType = 'string';

    const BRAND_SLIDER_IMAGE_PATH='uploads/brand/slider';

    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $authUserCode = getAuthUserCode();
            $model->brand_slider_code=$model->getBrandSliderCode();
            $model->created_by=$authUserCode;
            $model->updated_by=$authUserCode;
        });
        static::updating(function ($model){
            $authUserCode=getAuthUserCode();
            $model->updated_by=$authUserCode;
        });
        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }
    public function getBrandSliderCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BS', '1000', true);
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_code','brand_code');
    }
}
