<?php

namespace App\Modules\ContentManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;


class CompanyTimeline extends Model
{
    use ModelCodeGenerator;

    protected $table = 'company_timelines';
    protected $primaryKey = 'company_timeline_code';
    public $incrementing = false;


    protected $fillable = ['year','title','description','is_active'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->company_timeline_code = $model->generateCompanyTimelineCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });
    }

    public function generateCompanyTimelineCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'CT', '1000', false);
    }

}
