<?php
namespace App\Modules\Career\Repositories;

use App\Modules\Career\Models\Candidate;
use App\Modules\Application\Traits\UploadImage\ImageService;

class CandidateRepository{
    use ImageService;

    public function createCandidate($validateData){
        $validateData['cv_file'] = $this->storeImageInServer($validateData['cv_file'],Candidate::IMAGE_PATH);
        return Candidate::create($validateData)->fresh();
    }
    public function getAllCandidate(){
        return Candidate::get();
    }
    public function showCandidateDetail($candidateCode){
        return Candidate::where('candidate_code',$candidateCode)->first();
    }
}
