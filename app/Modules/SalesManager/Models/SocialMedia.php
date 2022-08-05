<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 22/4/2021
 * Time: 1:47 PM
 */


namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\SalesManager\Models\ManagerSMILink;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use SoftDeletes;

    use ModelCodeGenerator;

    protected $table = 'social_medias';
    protected $primaryKey = 'sm_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sm_code',
        'social_media_name',
        'base_url',
        'enabled_for_smi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->sm_code = $model->generateSocialMediaCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
        });
    }

    public function setSocialMediaNameAttribute($value)
    {
        $this->attributes['social_media_name'] = strtolower($value);
    }

    public function generateSocialMediaCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SM', '1000', true);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'user_code');
    }

    public function managerSMILinks()
    {
        return $this->hasMany(ManagerSMILink::class,'sm_code','sm_code');
    }


}
