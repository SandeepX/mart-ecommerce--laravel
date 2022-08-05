<?php

namespace App\Modules\Lead\Models;

use App\Modules\Application\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadDocument extends Model
{
    use SoftDeletes, SetTimeZone;

    protected $table = 'lead_documents';
    protected $primaryKey = 'lead_document_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'lead_document_code',
        'document_type',
        'document_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public $leadDocumentFolder = 'uploads/lead/documents/';


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->lead_document_code = $model->generateLeadDocumentCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public static function generateLeadDocumentCode()
    {
        $leadDocumentPrefix = 'LD';
        $initialIndex = '1000';
        $leadDocument = self::withTrashed()->latest('created_at')->first();
        if ($leadDocument) {
            $codeTobePad = (int) (str_replace($leadDocumentPrefix, "", $leadDocument->lead_document_code) + 1);
          //  $paddedCode = str_pad($codeTobePad, 8, '0', STR_PAD_LEFT);
            $latestLeadDocumentCode = $leadDocumentPrefix . $codeTobePad;
        } else {
            $latestLeadDocumentCode = $leadDocumentPrefix . $initialIndex;
        }
        return $latestLeadDocumentCode;
    }


    public function getLeadDocumentEnums()
    {
        return array_keys(config('lead-document-types')); // only keys 
    }

    public function getLeadDocumentOptions()
    {
        return config('lead-document-types'); // key => value
    }


    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_code');
    }
}
