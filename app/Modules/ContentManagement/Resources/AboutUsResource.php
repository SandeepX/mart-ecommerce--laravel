<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/1/2021
 * Time: 5:20 PM
 */

namespace App\Modules\ContentManagement\Resources;

use App\Modules\ContentManagement\Models\AboutUs;
use Illuminate\Http\Resources\Json\JsonResource;


class AboutUsResource extends JsonResource
{
    public function toArray($request)
    {
        return [

            'page_image' => (isset($this->page_image))?photoToUrl($this->page_image,url(AboutUs::PAGE_IMAGE_PATH)): NULL,
            'company_name' =>(isset($this->company_name))?$this->company_name:NULL,
            'company_description' =>(isset($this->company_description))?$this->company_description:NULL,
            'ceo_name'=>(isset($this->ceo_name))?$this->ceo_name:NULL,
            'message_from_ceo'=>(isset($this->message_from_ceo))?$this->message_from_ceo:NULL,
            'ceo_image'=>(isset($this->ceo_image))?photoToUrl($this->ceo_image,url(AboutUs::CEO_IMAGE_PATH)):NULL,
        ];
    }
    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }
}
