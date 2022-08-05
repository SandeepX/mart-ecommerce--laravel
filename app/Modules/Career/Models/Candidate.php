<?php

namespace App\Modules\Career\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use ModelCodeGenerator;

    protected $table = 'candidates';
    protected $primaryKey = 'candidate_code';
    protected $fillable = ['candidate_code','career_id','name','email','phone_number','gender','cover_letter','cv_file'];
    public $incrementing = false;
    const IMAGE_PATH='uploads/careers/candidates/';
    const RECORDS_PER_PAGE =10;
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->candidate_code = $model->generateCandidateCode();
        });
    }
    public function careers(){
       return $this->belongsTo(Career::class,'career_id','career_code')->withDefault();
    }
    public function generateCandidateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'CA', '1000', false);
    }
}
