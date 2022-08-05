<?php

namespace App\Modules\ContentManagement\Services;

use App\Modules\ContentManagement\Repositories\SitePageRepository;

class SitePageService
{
    private $sitePageRepository;
    public function __construct(SitePageRepository $sitePageRepository)
    {
        $this->sitePageRepository = $sitePageRepository;
    }

    public function getAllSitePages()
    {
        return $this->sitePageRepository->getAllSitePages();
    }

    public function findSitePageByContentType($sitePageContentType)
    {
        return $this->sitePageRepository->findOrFailSitePageByContentType($sitePageContentType);
    }

    public function updateSitePage($sitePage, $validatedSitePage)
    {
       return $this->sitePageRepository->updateSitePage($sitePage, $validatedSitePage);
    }
}