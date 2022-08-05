<?php

namespace App\Modules\LuckyDraw\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\LuckyDraw\Models\StoreLuckydraw;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

use Exception;

class StoreLuckydrawRepository
{

    use ImageService;

    public function findStoreLuckydrawByCode($storeLuckydrawCode)
    {
        return StoreLuckydraw::where('store_luckydraw_code', $storeLuckydrawCode)->first();
    }

    public function findOrFailStoreLuckydrawByCode($storeLuckydrawCode)
    {
        if($storeLuckydraw = $this->findStoreLuckydrawByCode($storeLuckydrawCode))
        {
            return $storeLuckydraw;
        }
        throw new ModelNotFoundException('No Such StoreLuckydraw Found !');
    }

    public function getAllLuckydraws()
    {
        return StoreLuckydraw::latest()->get();
    }

    public function getAllPaginatedLuckydraws($status,$perPage)
    {
        return StoreLuckydraw::where('status',$status)
            ->where('is_active',1)
            ->latest()
            ->paginate($perPage);
    }
    public function create($validatedData)
    {

        try {
            //handle Image
            if(!empty($validatedData['image'])) {
                $validatedData['image'] = $this->storeImageInServer($validatedData['image'], StoreLuckydraw::IMAGE_PATH);
            }
            $validatedData['slug'] = makeSlugWithHash($validatedData['luckydraw_name']);
            $validatedData['terms'] = json_encode($validatedData['terms']);
            return StoreLuckydraw::create($validatedData)->fresh();
        } catch (Exception $e) {
            $this->deleteImageFromServer(StoreLuckydraw::IMAGE_PATH, $validatedData['image']);
            throw $e;
        }
    }


    public function update($validatedData, $storeLuckydraw)
    {

        try {
            $validatedData['slug'] = Str::slug($validatedData['luckydraw_name']);

            if(!empty($validatedData['image'])){
                $this->deleteImageFromServer(StoreLuckydraw::IMAGE_PATH, $storeLuckydraw->store_logo);
                $validatedData['image'] = $this->storeImageInServer($validatedData['image'], StoreLuckydraw::IMAGE_PATH);
            }

            $validatedData['terms'] = json_encode($validatedData['terms']);
            $storeLuckydraw->update($validatedData);
            return $storeLuckydraw->fresh();
        } catch (Exception $e) {
            $this->deleteImageFromServer(StoreLuckydraw::IMAGE_PATH, $validatedData['image']);
            throw $e;
        }
    }


    public function delete($storeLuckydraw)
    {

        $storeLuckydraw->delete();
        return $storeLuckydraw;
    }

    public function changeStoreLuckydrawStatus(StoreLuckydraw $storeLuckydraw, $status){

        try{

            $storeLuckydraw->status = $status;
            $storeLuckydraw->save();

            return $storeLuckydraw;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function changeStoreLuckyDrawActiveStatus(StoreLuckydraw $storeLuckydraw,$status){

        try{

            $storeLuckydraw->is_active = $status;
            $storeLuckydraw->save();

            return $storeLuckydraw;
        }catch (Exception $exception){
            throw $exception;
        }

    }
}
