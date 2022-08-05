<?php

namespace App\Modules\ContentManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;


class Vision extends Model
{
    use ModelCodeGenerator;

    protected $table = 'visions';
    protected $primaryKey = 'vision_code';
    public $incrementing = false;
    const PAGE_IMAGE_PATH="uploads/contentManagement/page";


    protected $fillable = ['page_image','vision_description','mission_description','is_active'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->vision_code = $model->generateVisionMissionCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });
    }

    public function generateVisionMissionCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'VM', '1000', false);
    }

}
