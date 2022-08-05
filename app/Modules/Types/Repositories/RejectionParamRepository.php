<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\RejectionParam;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RejectionParamRepository
{
    private $rejectionParam;
    public function __construct(RejectionParam $rejectionParam)
    {
      $this->rejectionParam = $rejectionParam;  
    }

    public function getAllRejectionParams(){
        return $this->rejectionParam->latest()->get();
    }


    public function getAllTrashedRejectionParams($with = [])
    {
        return $this->rejectionParam->with($with)->withTrashed()->latest()->get();
    }

    public function findRejectionParamById($rejectionParamId, $with = [])
    {
        return $this->rejectionParam->with($with)->where('id', $rejectionParamId)->first();
    }

    public function findRejectionParamBySlug($rejectionParamSlug, $with = [])
    {
        return $this->rejectionParam->with($with)->where('slug', $rejectionParamSlug)->first();
    }


    public function findRejectionParamByCode($rejectionParamCode, $with = [])
    {
        return $this->rejectionParam->with($with)->where('rejection_code', $rejectionParamCode)->first();
    }


    public function findOrFailRejectionParamById($rejectionParamId, $with = [])
    {
        if(!$rejectionParam = $this->findRejectionParamById($rejectionParamId,$with)){
            throw new ModelNotFoundException('No Such Rejection Param Found');
        }
        return $rejectionParam;
    }

    public function findOrFailRejectionParamBySlug($rejectionParamSlug, $with = [])
    {
        if(!$rejectionParam = $this->findRejectionParamBySlug($rejectionParamSlug,$with)){
            throw new ModelNotFoundException('No Such Rejection Param Found');
        }
        return $rejectionParam;
    }


    public function findOrFailRejectionParamByCode($rejectionParamCode, $with = [])
    {
        if(!$rejectionParam = $this->findRejectionParamByCode($rejectionParamCode,$with)){
            throw new ModelNotFoundException('No Such Rejection Param Found');
        }
        return $rejectionParam;
    }


    public function storeRejectionParam($validated)
    {
        $authUserCode = getAuthUserCode();
        $validated['rejection_code'] = $this->rejectionParam->generateRejectionParamCode();
        $validated['slug'] = make_slug($validated['rejection_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $rejectionParam = $this->rejectionParam->create($validated);
        return $rejectionParam->fresh();

    }

    public function updateRejectionParam($validated, $rejectionParam)
    {
        $validated['slug'] = make_slug($validated['rejection_name']);
        $validated['updated_by'] = getAuthUserCode();
        $rejectionParam->update($validated);
        return $rejectionParam;

    }

    public function delete($rejectionParam)
    {
        $rejectionParam->delete();
        $rejectionParam->deleted_by= getAuthUserCode();
        $rejectionParam->save();
        return $rejectionParam;
    }





}