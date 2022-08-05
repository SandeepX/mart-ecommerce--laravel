<?php

namespace App\Modules\Career\Models;

use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOpening extends Model
{
    use SoftDeletes, IsActiveScope, ModelCodeGenerator;

    protected $table = 'job_openings';
    protected $primaryKey = 'opening_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const JOB_TYPES=[
        'Full Time'=>'full_time',
        'Part Time' =>'part_time',
        'Intern' =>'intern'
    ];

    protected $fillable = [
        'opening_code',
        'title','slug',
        'location',
        'description',
        'requirements',
        'salary','job_type',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->opening_code = $model->generateJobOpeningCode();
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

    public function generateJobOpeningCode()
    {
        return $this->generateModelCode($this, $this->primaryKey, 'JO-', '001', 3);
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public function jobQuestions(){
        return $this->belongsToMany(JobQuestion::class, 'job_opening_question', 'job_opening_code', 'job_question_code')
            ->withPivot('priority')->withTimestamps();
    }
}
