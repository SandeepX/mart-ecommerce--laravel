<?php

namespace App\Modules\Career\Models;

use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Location\Models\LocationHierarchy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use SoftDeletes, IsActiveScope, ModelCodeGenerator;

    protected $table = 'job_applications';
    protected $primaryKey = 'application_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['application_code', 'job_opening_code', 'name', 'email',
        'gender',
        'phone_num',
        'other_contacts',
        'temp_location_code',
        'temp_local_address',
        'perm_location_code',
        'perm_local_address'
    ];

    const DOCUMENT_TYPES=['cv'];

    const IMAGE_PATH='uploads/job_applications/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->application_code = $model->generateJobApplicationCode();
            $model->tracking_code = $model->generateTrackingCode();
        });


        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function generateJobApplicationCode()
    {
        return $this->generateModelCode($this, $this->primaryKey, 'JA-', '001', 3);
    }

    public function generateTrackingCode()
    {
        return $this->generateModelCode($this, 'tracking_code', 'JAT-', '001', 3);
    }


    public function tempLocation(){
        return $this->belongsTo(LocationHierarchy::class, 'temp_location_code')->withDefault();
    }

    public function permanentLocation(){
        return $this->belongsTo(LocationHierarchy::class, 'perm_location_code')->withDefault();
    }

    public function jobOpening(){
        return $this->belongsTo(JobOpening::class, 'job_opening_code')->withDefault();
    }

    public function applicationDocuments(){
        return $this->hasMany(JobApplicationDocument::class,'job_application_code');
    }

    public function answers(){
        return $this->hasMany(JobApplicationAnswer::class,'job_application_code');
    }
}
