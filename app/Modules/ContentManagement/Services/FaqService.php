<?php

namespace App\Modules\ContentManagement\Services;

use App\Modules\ContentManagement\Repositories\FaqRepository;

class FaqService
{
    private $faqRepository;
    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function findFaqByCode($faqCode)
    {
        return $this->faqRepository->findFaqByCode($faqCode);
    }

    public function getAllFaqs()
    {
        return $this->faqRepository->getAllFaqs();
    }

    public function storeFaq($validatedFaq)
    {   
        $this->faqRepository->storeFaq($validatedFaq);
    }

    public function updateFaq($validatedFaq, $faqCode)
    {
        if(!isset($validatedFaq['is_active']))
            $validatedFaq['is_active'] = 0;
        $faq = $this->faqRepository->findFaqByCode($faqCode);
        $this->faqRepository->updateFaq($validatedFaq, $faq);
    }

    public function deleteFaq($faqCode)
    {
        $faq = $this->faqRepository->findFaqByCode($faqCode);
        $this->faqRepository->deleteFaq($faq);
    }
}