<?php

namespace App\Modules\Career\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationDocument extends Model
{
    protected $table = 'job_application_documents';
    protected $fillable = ['job_application_code', 'document', 'document_type',];

    public function jobApplication(){
        return $this->belongsTo(JobApplication::class, 'job_application_code')->withDefault();
    }
}
