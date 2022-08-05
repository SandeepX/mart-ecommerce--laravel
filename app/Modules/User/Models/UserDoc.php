<?php

namespace App\Modules\User\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserDoc extends Model
{
    use ModelCodeGenerator;
    protected $table = 'user_docs';
    protected $primaryKey = 'user_doc_code';
    public $incrementing = false;
    protected $fillable = [
        'user_code',
        'doc_name',
        'doc_number',
        'doc',
        'doc_issued_district',
        'is_verified',
        'verified_by'
    ];

    const DOCUMENT_PATH = 'uploads/user/documents/';

    const MANAGER_DOC_TYPES = [
        'citizenship',
        'pan_card',
        'slc_see_certificate',
        'plus_2_certificate',
        'bachelors_certificate',
        'masters_certificate'
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_doc_code = $model->generateUserDocCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateUserDocCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'UDC', '1000', false);
    }

    public function getDocumentImagePath(){
        return photoToUrl($this->doc,asset(self::DOCUMENT_PATH));
    }

}
