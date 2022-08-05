<?php


namespace App\Modules\QuizGame\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizSubmission extends Model
{
    use ModelCodeGenerator;

    protected $table = 'quiz_submissions';
    protected $primaryKey = 'quiz_submission_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'qp_code',
        'participator_type',
        'participator_code',
        'submitted_date',
        'submitted_by'
    ];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->quiz_submission_code = $model->generateQuizSubmissionCode();
            $model->submitted_by = getAuthUserCode();
            $model->submitted_date = Carbon::today()->format('Y-m-d');
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateQuizSubmissionCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'QS', '1000', false);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'user_code');
    }

    public function quizPassage()
    {
        return $this->belongsTo(QuizPassage::class, 'qp_code', 'qp_code');
    }


    public function quizSubmissionDetail()
    {
        return $this->hasMany(QuizSubmissionDetail::class,'quiz_submission_code','quiz_submission_code');
    }

}




