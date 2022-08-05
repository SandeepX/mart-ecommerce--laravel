<?php

namespace App\Modules\ContentManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaticPageImage extends Model
{
    use ModelCodeGenerator;
    use SoftDeletes;

    protected $table = 'static_page_image';

    protected $primaryKey = 'static_page_image_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'static_page_image_code',
        'image',
        'page_name',
        'is_active',
        'created_by',
        'updated_by'
    ];

    const PAGE_NAMES = ['store-finder','allpasal-mart-register','allpasal-mini-mart-register'];

    const DOCUMENT_PATH = 'uploads/content-management/static-page-images/';


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->static_page_image_code = $model->generateStaticPageImageCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function  generateStaticPageImageCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SPIC', '1000', true);
    }

}
