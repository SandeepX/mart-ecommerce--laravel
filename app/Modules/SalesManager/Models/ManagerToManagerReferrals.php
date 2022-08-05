<?php

namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class ManagerToManagerReferrals extends Model
{

    use ModelCodeGenerator;
    protected $table = 'manager_to_manager_referrals';

    protected $primaryKey = 'manager_to_manager_referrals_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'manager_to_manager_referrals_code',
        'manager_code',
        'referred_manager_code',
        'created_by',
        'updated_by'
    ];

    const ROWS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->manager_to_manager_referrals_code = $model->generateCode();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MMRC', 1000, false);
    }


    public function referredManager(){
        return $this->belongsTo(Manager::class,'referred_manager_code','manager_code');
    }




}
