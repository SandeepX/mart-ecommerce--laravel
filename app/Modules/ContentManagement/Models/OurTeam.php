<?php

namespace App\Modules\ContentManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurTeam extends Model
{
    use ModelCodeGenerator,SoftDeletes;
    protected $table = 'our_teams';
    protected $primaryKey = 'our_team_code';
    public $incrementing = false;

    protected $fillable = ['name','image','department','delegation','message','is_active'];
    const TEAM_IMAGE_PATH ="uploads/contentManagement/our-team";

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->our_team_code = $model->generateOurTeamCode();
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

    public function generateOurTeamCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'OT', '1000', true);
    }
}
