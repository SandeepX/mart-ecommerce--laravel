<?php

namespace App\Modules\Career\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use ModelCodeGenerator;
    protected $table = 'careers';
    protected $primaryKey = 'career_code';
    public $incrementing = false;
    protected $fillable = ['career_code','title','slug','is_active','descriptions','created_by'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->created_by = $authUserCode;
            $model->career_code = $model->generateCareerCode();
        });
        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->created_by = $authUserCode;
        });
    }

    public function candidates(){
        return $this->hasMany(Candidate::class,'career_id','career_code');
    }
    public function generateCareerCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'CR', '1000',false);
    }

}
