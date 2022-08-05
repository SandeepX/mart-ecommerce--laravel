<?php

namespace App\Modules\ContentManagement\Repositories;

use App\Modules\ContentManagement\Models\SitePage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SitePageRepository
{
    public function findSitePageByContentType($sitePageContentType)
    {
        $sitePage = SitePage::where('content_type', $sitePageContentType)->first();
        if($sitePage){
            return $sitePage;
        }

        throw new ModelNotFoundException('No such site page found!', 404);
    }

    public function getAllSitePages()
    {
        return SitePage::all();
    }
    
    public function findOrFailSitePageByContentType($sitePageContentType)
    {
        return $this->findSitePageByContentType($sitePageContentType);
    }
    
    public function updateSitePage($sitePage, $validatedSitePage)
    {
        $data['content'] = $validatedSitePage['content'];
        $sitePage->update($data);
        return $sitePage->fresh();
    }
}