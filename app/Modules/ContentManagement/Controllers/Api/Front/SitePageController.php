<?php

namespace App\Modules\ContentManagement\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\ContentManagement\Services\SitePageService;
use App\Modules\ContentManagement\Services\FaqService;

class SitePageController extends Controller
{
    private $sitePageService,$faqService;
    public function __construct(
        SitePageService $sitePageService,
        FaqService $faqService
        )
    {
        $this->sitePageService = $sitePageService;
        $this->faqService = $faqService;
    }

    public function getAboutUsContent()
    {
        $content = $this->sitePageService->findSitePageByContentType('about-us');
        return sendSuccessResponse('Data Found!', $content);
    }

    public function getPrivacyPolicyContent()
    {
        $content = $this->sitePageService->findSitePageByContentType('privacy-policy');
        return sendSuccessResponse('Data Found!', $content);
    }


    public function getTermsAndConditionsContent()
    {
        $content = $this->sitePageService->findSitePageByContentType('terms-and-conditions');
        return sendSuccessResponse('Data Found!', $content);
    }

    public function getFaqs()
    {
        $faqs = $this->faqService->getAllFaqs();
        return sendSuccessResponse('Data Found!', $faqs);
    }
}