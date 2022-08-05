<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/1/2021
 * Time: 5:20 PM
 */

namespace App\Modules\ContentManagement\Resources;

use App\Modules\ContentManagement\Models\OurTeam;
use Illuminate\Http\Resources\Json\JsonResource;


class OurTeamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'image' => (isset($this->image))?photoToUrl($this->image,url(OurTeam::TEAM_IMAGE_PATH)): NULL,
            'name' =>(isset($this->name))?$this->name:NULL,
            'department' =>(isset($this->department))?$this->department:NULL,
            'delegation'=>(isset($this->delegation))?$this->delegation:NULL,
            'message'=>(isset($this->message))?$this->message:NULL,
        ];
    }
}
