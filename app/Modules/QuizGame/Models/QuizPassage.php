<?php


namespace App\Modules\QuizGame\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizPassage extends Model
{
    use ModelCodeGenerator;

    protected $table = 'quiz_game_passages';
    protected $primaryKey = 'qp_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'passage_title',
        'passage',
        'passage_is_active',
        'total_passage_points',
        'created_by',
        'updated_by'

    ];

    const RECORDS_PER_PAGE = 10;


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->qp_code = $model->generateQuizPassageCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

        static::deleting(function($quizPassageDetail) {
            $quizPassageDetail->quizDates()->delete();
            $quizPassageDetail->quizQuestions()->delete();
        });
    }

    public function generateQuizPassageCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'QP', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function quizDates()
    {
       return $this->hasMany(QuizDate::class,'qp_code','qp_code');
    }

    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class,'qp_code','qp_code');
    }

    public function isActiveQuizQuestion()
    {
        return $this->hasMany(QuizQuestion::class,'qp_code','qp_code')
            ->where('question_is_active',1);
    }

    public function quizSubmission()
    {
        return $this->hasMany(QuizSubmission::class,'qp_code','qp_code');
    }


}


