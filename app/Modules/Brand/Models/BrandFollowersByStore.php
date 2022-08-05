<?php

namespace App\Modules\Brand\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandFollowersByStore extends Model
{
    use ModelCodeGenerator,SoftDeletes;
    protected $table = 'brand_followers_by_stores';
    protected $primaryKey='brand_followers_by_store';
    protected $fillable = ['brand_code','store_code'];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot(){
        parent::boot();

        static::creating(function ($model){
            $authStoreCode=getAuthStoreCode();
            $model->store_code=$authStoreCode;
            $model->brand_followers_by_store=$model->generateBrandFollowersByStore();
        });

    }
    public function generateBrandFollowersByStore(){
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BFS', '1000', true);
    }
    public function brands(){
        return $this->belongsTo(Brand::class,'brand_code','brand_code');
    }
}
