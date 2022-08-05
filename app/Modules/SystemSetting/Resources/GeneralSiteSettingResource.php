<?php

namespace App\Modules\SystemSetting\Resources;

use App\Modules\SystemSetting\Models\SeoSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralSiteSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $seoSetting = SeoSetting::first();

        return [
            'id' => $this->id,
            'logo' => photoToUrl($this->logo,asset($this->uploadFolder)),
            'fav_icon' => photoToUrl($this->favicon,asset($this->uploadFolder)),
            'company_address' => $this->full_address,
            'primary_contact' => $this->primary_contact,
            'secondary_contact' => $this->secondary_contact,
            'primary_bank_name' => $this->primary_bank_name,
            'primary_bank_account_number' => $this->primary_bank_account_number,
            'primary_bank_branch' => $this->primary_bank_branch,
            'secondary_bank_name' => $this->secondary_bank_name,
            'secondary_bank_account_number' => $this->secondary_bank_account_number,
            'secondary_bank_branch' => $this->secondary_bank_branch,
            'company_email' => $this->company_email,
            'company_brief' => $this->company_brief,
            'social_media_links' => [
                'facebook' => $this->facebook,
                'twitter'  => $this->twitter,
                'instagram' => $this->instagram
            ],
            'seo'=> [
                'meta_title' => ($seoSetting) ? $seoSetting->meta_title : '' ,
                'meta_description' => ($seoSetting) ? $seoSetting->meta_description : '',
                'keywords' => ($seoSetting) ? implode(',',json_decode($seoSetting->keywords)) : '',
                'author' => ($seoSetting) ? $seoSetting->author : ''

            ]
        ];
    }
}
