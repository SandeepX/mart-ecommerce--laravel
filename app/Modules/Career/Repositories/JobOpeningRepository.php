<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/16/2020
 * Time: 4:31 PM
 */

namespace App\Modules\Career\Repositories;


use App\Modules\Career\Models\JobOpening;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobOpeningRepository
{
    public function getJobTypes(){

        return JobOpening::JOB_TYPES;
    }

    public static function getJobTypesValue(){

        return JobOpening::JOB_TYPES;
    }

    public function getAll($activeStatus=false){

        $jobOpening = JobOpening::query();

        if ($activeStatus){
            $jobOpening = $jobOpening->active();
        }

        return $jobOpening->latest()->get();
    }

    public function getAllWith($activeStatus=false,array $with){

        $jobOpening = JobOpening::with($with);

        if ($activeStatus){
            $jobOpening = $jobOpening->active();
        }

        return $jobOpening->latest()->get();
    }


    public function findOrFailByCode($code){

        $jobQuestion = JobOpening::where('opening_code',$code)->first();

        if (!$jobQuestion){
            throw new ModelNotFoundException('Job Opening not found for the code');
        }

        return $jobQuestion;
    }

    public function findOrFailByCodeWith($code,array $with){

        $jobQuestion = JobOpening::with($with)->where('opening_code',$code)->first();

        if (!$jobQuestion){
            throw new ModelNotFoundException('Job Opening not found for the code');
        }

        return $jobQuestion;
    }

    public function findOrFailBySlug($slug){

        $jobQuestion = JobOpening::where('slug',$slug)->first();

        if (!$jobQuestion){
            throw new ModelNotFoundException('Job Opening not found for the slug');
        }

        return $jobQuestion;
    }

    public static function findOrFailBySlugWith($slug,array $with){

        $jobQuestion = JobOpening::with($with)->where('slug',$slug)->first();

        if (!$jobQuestion){
            throw new ModelNotFoundException('Job Opening not found for the slug');
        }

        return $jobQuestion;
    }

    public function saveWithJobQuestion($data,$jobQuestions){

        $jobOpening=$this->save($data);

        $jobOpening->jobQuestions()->attach($jobQuestions);

        return $jobOpening;
    }

    public function save($data){
        $jobOpening= JobOpening::create($data)->fresh();
        return $jobOpening;
    }

    public function update(JobOpening $jobOpening,$data){
        $jobOpening->update($data);
        return $jobOpening->fresh();
    }

    public function updateWithJobQuestion(JobOpening $jobOpening,$data,$jobQuestions){

        $jobOpening=$this->update($jobOpening,$data);

        $jobOpening->jobQuestions()->sync($jobQuestions);

        return $jobOpening;
    }

    public function delete(JobOpening $jobOpening) {
        $jobOpening->delete();
        return $jobOpening;
    }

}