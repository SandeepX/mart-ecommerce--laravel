<?php

namespace App\Modules\Career\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationAnswer extends Model
{
    protected $table = 'job_application_answers';
    protected $fillable = ['job_application_code', 'question_code', 'answer', 'audio_answer',];

    public function jobApplication(){
        return $this->belongsTo(JobApplication::class, 'job_application_code')->withDefault();
    }

    public function jobQuestion(){
        return $this->belongsTo(JobQuestion::class, 'question_code')->withDefault();
    }
}
