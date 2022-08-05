<?php

namespace App\Modules\Lead\Models;

use App\Modules\Application\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes, SetTimeZone;
    protected $table = 'leads_detail';

    protected $primaryKey = 'lead_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'lead_name',
        'lead_code',
        'lead_location_code',
        'lead_landmark',
        'landmark_latitude',
        'landmark_longitude',
        'lead_phone_no',
        'lead_alternative_phone_no',
        'lead_email',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->lead_code = $model->generateLeadCode();
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

    public static function generateLeadCode()
    {
        $leadPrefix = 'L';
        $initialIndex = '1000';
        $lead = self::withTrashed()->latest('id')->first();
        if ($lead) {
            $codeTobePad = (int) (str_replace($leadPrefix, "", $lead->lead_code) + 1);
            //$paddedCode = str_pad($codeTobePad, 6, '0', STR_PAD_LEFT);
            $latestLeadCode = $leadPrefix . $codeTobePad;
        } else {
            $latestLeadCode = $leadPrefix . $initialIndex;
        }
        return $latestLeadCode;
    }


    public function documents()
    {
        return $this->hasMany(LeadDocument::class, 'lead_code');
    }
}
