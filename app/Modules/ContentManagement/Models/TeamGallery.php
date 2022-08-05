<?php

namespace App\Modules\ContentManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamGallery extends Model
{
    use ModelCodeGenerator,SoftDeletes;

    protected $table = 'team_galleries';
    protected $fillable = ['image','description','is_active'];

    const TEAM_GALLERY_PATH = "uploads/contentManagement/team-gallery";

    public static function boot(){
        parent::boot();

        static::creating(function ($model){
            $authUserCode = getAuthUserCode();
            $model->team_gallery_code= $model->getTeamGalleryCode();
            $model->created_by=$authUserCode;
            $model->updated_by =$authUserCode;

        });
        static::updating(function ($model){
            $model->updated_by=getAuthUserCode();
        });
        static::deleting(function ($model){
            $model->deleted_by=getAuthUserCode();
        });
    }
    public function getTeamGalleryCode(){
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'TG', '1000', true);

    }
}
