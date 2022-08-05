<?php

namespace App\Modules\Application\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class InvalidEmailCheckerAPI extends Model
{
    use ModelCodeGenerator;
    protected $table = 'invalid_email_checker_api';
    protected $primaryKey = 'email_api_code';
    protected $fillable = ['email_api_key'];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot(){
        parent::boot();

        static::creating(function ($model){
            $model->email_api_code = $model->generateEmailApiCode();
        });

    }
    public function generateEmailApiCode(){
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IEAK', '1000', false);
    }
}
