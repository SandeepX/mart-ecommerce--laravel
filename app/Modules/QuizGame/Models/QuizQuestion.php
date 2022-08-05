<?php


namespace App\Modules\QuizGame\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizQuestion extends Model
{
    use ModelCodeGenerator;

    protected $table = 'quiz_game_questions';
    protected $primaryKey = 'question_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'qp_code',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'points',
        'question_is_active',
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
            $model->question_code = $model->generateQuizQuestionCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

    }

    public function generateQuizQuestionCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'QQ', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function quizPassageQuestion()
    {
        return $this->belongsTo(QuizPassage::class, 'qp_code', 'qp_code');
    }

    public function quizSubmittedQuestion()
    {
        return $this->hasMany(QuizSubmission::class, 'question_code', 'question_code');
    }

}



