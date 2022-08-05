<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/18/2020
 * Time: 12:38 PM
 */

namespace App\Modules\Career\Repositories;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Career\Models\JobApplication;
use App\Modules\Career\Models\JobOpening;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobApplicationRepository
{
    use ImageService;

    public function getDocumentTypes(){

        return JobApplication::DOCUMENT_TYPES;
    }

    public function getAll(){

        return JobApplication::latest()->get();
    }


    public function getAllWith(array $with){

        return JobApplication::with($with)->latest()->get();
    }

    public function findOrFailByCode($code){

        $jobApplication = JobApplication::where('application_code',$code)->first();

        if (!$jobApplication){
            throw new ModelNotFoundException('Job Application not found for the code');
        }

        return $jobApplication;
    }

    public function findOrFailByCodeWith($code,array $with){

        $jobApplication = JobApplication::with($with)->where('application_code',$code)->first();

        if (!$jobApplication){
            throw new ModelNotFoundException('Job Application not found for the code');
        }

        return $jobApplication;
    }

    public function save(JobOpening $jobOpening,$validatedData){

        try{
            if (isset($validatedData['other_contacts'])){
                $validatedData['other_contacts'] =json_encode(array_filter($validatedData['other_contacts']));
            }

            $validatedData['job_opening_code'] = $jobOpening->opening_code;
            return JobApplication::create($validatedData)->fresh();
        }catch (Exception $e){
            throw $e;
        }

    }

    public function saveJobApplicationAnswers(JobApplication $jobApplication,$answers){

        try{
            $jobApplication->answers()->createMany($answers);
        }catch (Exception $e){
            throw $e;
        }

    }

    public function saveJobApplicationDocument(JobApplication $jobApplication,$document,$documentType){

        $fileNameToStore='';
        try{
            if (!in_array($documentType,$this->getDocumentTypes())){
                throw new Exception('Invalid document type');
            }
            $fileNameToStore = $this->storeImageInServer($document, JobApplication::IMAGE_PATH);

            $jobApplication->applicationDocuments()->create([
                'document' => $fileNameToStore,
                'document_type' => $documentType
            ]);

        }catch (Exception $e){

            $this->deleteImageFromServer(JobApplication::IMAGE_PATH,$fileNameToStore);
            throw $e;
        }

    }

}