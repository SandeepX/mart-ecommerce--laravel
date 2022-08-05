<?php

namespace App\Modules\Questionnaire\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActionVerificationQuestionnaire extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'action_verification_questionnaire';
    protected $primaryKey = 'avq_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'avq_code',
        'action',
        'entity',
        'question',
        'is_active',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE=10;

    const action = ['miscellaneous_payment_verification','dispatch_route_verification'];
    const entity = ['balance','orders'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->avq_code = $model->generateActionVerificationQuestionnaireCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateActionVerificationQuestionnaireCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'AVQC', '1000', true);
    }

}
