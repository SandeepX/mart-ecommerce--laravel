<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\CancellationParam;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CancellationParamRepository
{
    private $cancellationParam;
    public function __construct(CancellationParam $cancellationParam)
    {
      $this->cancellationParam = $cancellationParam;  
    }

    public function getAllCancellationParams(){
        return $this->cancellationParam->latest()->get();
    }


    public function getAllTrashedCancellationParams($with = [])
    {
        return $this->cancellationParam->with($with)->withTrashed()->latest()->get();
    }

    public function findCancellationParamById($cancellationParamId, $with = [])
    {
        return $this->cancellationParam->with($with)->where('id', $cancellationParamId)->first();
    }

    public function findCancellationParamBySlug($cancellationParamSlug, $with = [])
    {
        return $this->cancellationParam->with($with)->where('slug', $cancellationParamSlug)->first();
    }


    public function findCancellationParamByCode($cancellationParamCode, $with = [])
    {
        return $this->cancellationParam->with($with)->where('cancellation_code', $cancellationParamCode)->first();
    }


    public function findOrFailCancellationParamById($cancellationParamId, $with = [])
    {
        if(!$cancellationParam = $this->findCancellationParamById($cancellationParamId,$with)){
            throw new ModelNotFoundException('No Such Cancellation Param Found');
        }
        return $cancellationParam;
    }

    public function findOrFailCancellationParamBySlug($cancellationParamSlug, $with = [])
    {
        if(!$cancellationParam = $this->findCancellationParamBySlug($cancellationParamSlug,$with)){
            throw new ModelNotFoundException('No Such Cancellation Param Found');
        }
        return $cancellationParam;
    }


    public function findOrFailCancellationParamByCode($cancellationParamCode, $with = [])
    {
        if(!$cancellationParam = $this->findCancellationParamByCode($cancellationParamCode,$with)){
            throw new ModelNotFoundException('No Such Cancellation Param Found');
        }
        return $cancellationParam;
    }


    public function storecancellationParam($validated)
    {
        $authUserCode = getAuthUserCode();
        $validated['cancellation_code'] = $this->cancellationParam->generateCancellationParamCode();
        $validated['slug'] = make_slug($validated['cancellation_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $cancellationParam = $this->cancellationParam->create($validated);
        return $cancellationParam->fresh();

    }

    public function updatecancellationParam($validated, $cancellationParam)
    {
        $validated['slug'] = make_slug($validated['cancellation_name']);
        $validated['updated_by'] = getAuthUserCode();
        $cancellationParam->update($validated);
        return $cancellationParam;

    }

    public function delete($cancellationParam)
    {
        $cancellationParam->delete();
        $cancellationParam->deleted_by= getAuthUserCode();
        $cancellationParam->save();
        return $cancellationParam;
    }





}