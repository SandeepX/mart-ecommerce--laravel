<?php

namespace App\Modules\ContentManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutUs extends Model
{

    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'aboutus';
    protected $primaryKey = 'aboutUs_code';
    public $incrementing = false;
    const PAGE_IMAGE_PATH="uploads/contentManagement/page";
    const CEO_IMAGE_PATH="uploads/contentManagement/ceo";

    protected $fillable = ['page_image','company_name','company_description','ceo_name','message_from_ceo','ceo_image','is_active'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->aboutUs_code = $model->generateAboutUsCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });
    }

    public function generateAboutUsCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'AB', '1000', true);
    }

}
