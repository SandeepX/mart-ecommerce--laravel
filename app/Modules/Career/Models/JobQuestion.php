<?php

namespace App\Modules\Career\Models;

use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobQuestion extends Model
{
    use SoftDeletes, IsActiveScope, ModelCodeGenerator;

    protected $table = 'job_questions';
    protected $primaryKey = 'question_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['question_code',
        'question',
        'slug',
        'is_active',
        'created_by',
        'updated_by'];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->question_code = $model->generateJobQuestionCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function generateJobQuestionCode()
    {
        return $this->generateModelCode($this, $this->primaryKey, 'JQ-', '001', 3);
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

}
