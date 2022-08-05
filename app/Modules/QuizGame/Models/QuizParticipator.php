<?php


namespace App\Modules\QuizGame\Models;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizParticipator extends Model
{
    use ModelCodeGenerator;

    protected $table = 'quiz_participator_detail';
    protected $primaryKey = 'qpd_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'participator_type',
        'participator_code',
        'store_name',
        'store_pan_no',
        'store_location_ward_code',
        'store_full_location',
        'recharge_phone_no',
        'status',
        'status_reponded_at',
        'remarks',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 10;

    const STATUS = ['pending','approved','rejected'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->qpd_code = $model->generateQuizParticipatorDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

    }

    public function generateQuizParticipatorDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'QPD', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function quizSubmission()
    {
        return $this->hasMany(QuizSubmission::class,'participator_code','participator_code')
            ->where('submitted_date',Carbon::today());
    }


}




