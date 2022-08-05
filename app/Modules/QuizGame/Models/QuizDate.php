<?php


namespace App\Modules\QuizGame\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuizDate extends Model
{
    use ModelCodeGenerator;

    protected $table = 'quiz_passage_dates';
    protected $primaryKey = 'qpd_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'qpd_code',
        'qp_code',
        'quiz_passage_date'
    ];

    const RECORDS_PER_PAGE = 10;


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->qpd_code = $model->generateQuizDateCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateQuizDateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'QPD', '1000', false);
    }

    public function quizPassage()
    {
        return $this->belongsTo(QuizPassage::class, 'qp_code', 'qp_code');
    }

}



