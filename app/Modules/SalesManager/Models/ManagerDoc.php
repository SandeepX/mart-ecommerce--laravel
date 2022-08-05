<?php

namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ManagerDoc extends Model
{
    use ModelCodeGenerator;
    protected $table = 'manager_docs';
    protected $primaryKey = 'manager_doc_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'manager_doc_code',
        'manager_code',
        'doc_name',
        'doc',
        'is_verified',
        'verified_by',
        'doc_number',
        'doc_issued_district'
    ];

    const DOCUMENT_PATH = 'uploads/manager/documents/';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->manager_doc_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MDC', 1000, false);
    }
    public function getDocumentImagePath(){
        return photoToUrl($this->doc,asset(self::DOCUMENT_PATH));
    }
}
