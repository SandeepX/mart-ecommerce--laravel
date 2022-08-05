<?php

namespace App\Modules\Home\Models;

use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use SoftDeletes,IsActiveScope,ModelCodeGenerator;
    protected $table = 'sliders';
    protected $primaryKey = 'slider_code';
    public $incrementing = false;
    protected $keyType = 'string';

    public $uploadFolder = 'uploads/home/sliders/';


    protected $fillable = [
        'slider_code',
        'is_active',
        'slider_image',
        'slider_url',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->slider_code = $model->generateSliderCode();
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


    public function generateSliderCode()
    {
        return $this->generateModelCode($this, $this->primaryKey, 'SLI-', '001', 3);
    }




}