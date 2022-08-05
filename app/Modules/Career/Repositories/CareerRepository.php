<?php
namespace App\Modules\Career\Repositories;

use App\Modules\Career\Models\Career;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CareerRepository{

    public function findCareerBySlug($career){
        return Career::where('slug',$career)->first();
    }

    public function getAllCareer(){
        return Career::withCount('candidates')->latest()->get();

    }

    public function findOrFailByCode($code){

        $career = Career::where('career_code',$code)->first();
        if (!$career){
            throw new ModelNotFoundException('Job question not found for the code');
        }
        return $career;
    }
    public function findCareerByCode($careerCode){
        return Career::where('career_code',$careerCode)->first();
    }

    public function createCareer($validateData){
        $validateData['slug']= make_Slug($validateData['title']);
         Career::create($validateData)->fresh();
    }

    public function updateCareer($validatedData,$career)
    {
        $validatedData['slug'] = make_slug($validatedData['title']);
        $career->update($validatedData);
        return $career->fresh();
    }

    public function deleteCareer($career){
        $career->delete();
        return $career;
    }
}
