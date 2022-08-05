<?php


namespace App\Modules\QuizGame\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizSubmissionDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'quiz_submission_detail';
    protected $primaryKey = 'qsd_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'qsd_code',
        'quiz_submission_code',
        'question_code',
        'question',
        'correct_option',
        'answer'
    ];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->qsd_code = $model->generateQuizSubmissionDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

//    public static function generateCode()
//    {
//        $prefix = 'QSD';
//        $initialIndex = '1000';
//        $qsdCode= self::latest('id')->first();
//        if($qsdCode){
//            $codeTobePad = (int) (str_replace($prefix,"",$qsdCode->qsd_code) + 1 );
//            $latestCode = $prefix.$codeTobePad;
//        }else{
//            $latestCode = $prefix.$initialIndex;
//        }
//        return $latestCode;
//    }

    public function generateQuizSubmissionDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'QSD', '1000', false);
    }

    public function quizSubmission()
    {
        return $this->belongsTo(QuizSubmission::class, 'quiz_submission_code', 'quiz_submission_code');
    }
}




