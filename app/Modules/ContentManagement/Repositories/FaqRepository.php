<?php

namespace App\Modules\ContentManagement\Repositories;

use App\Modules\ContentManagement\Models\Faq;

class FaqRepository
{   
    public function findFaqByCode($faqCode)
    {
        return Faq::findOrFail($faqCode);
    }
    
    public function getAllFaqs()
    {
        return Faq::orderBy('priority', 'asc')->get();
    }
    
    public function storeFaq($validatedFaq)
    {
        Faq::create($validatedFaq);
    }

    public function updateFaq($validatedFaq, $faq)
    {
        $faq->update($validatedFaq);
    }

    public function deleteFaq($faq)
    {
        $faq->delete();
    }
}