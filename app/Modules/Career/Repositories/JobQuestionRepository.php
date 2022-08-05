<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/15/2020
 * Time: 1:03 PM
 */

namespace App\Modules\Career\Repositories;


use App\Modules\Career\Models\JobQuestion;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobQuestionRepository
{

    public function getAll($activeStatus=false){

        $jobQuestion = JobQuestion::query();

        if ($activeStatus){
            $jobQuestion = $jobQuestion->active();
        }

        return $jobQuestion->latest()->get();
    }

    public function findOrFailByCode($code){

        $jobQuestion = JobQuestion::where('question_code',$code)->first();

        if (!$jobQuestion){
            throw new ModelNotFoundException('Job question not found for the code');
        }

        return $jobQuestion;
    }

    public function findOrFailBySlug($slug){

        $jobQuestion = JobQuestion::where('slug',$slug)->first();

        if (!$jobQuestion){
            throw new ModelNotFoundException('Job question not found for the slug');
        }

        return $jobQuestion;
    }

    public function save($data){
        return JobQuestion::create($data)->fresh();
    }

    public function update(JobQuestion $jobQuestion,$data){
        $jobQuestion->update($data);
        return $jobQuestion->fresh();
    }

    public function delete(JobQuestion $jobQuestion) {
        $jobQuestion->delete();
        return $jobQuestion;
    }
}