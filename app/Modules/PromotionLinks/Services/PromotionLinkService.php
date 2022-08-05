<?php

namespace App\Modules\PromotionLinks\Services;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\PromotionLinks\Models\PromotionLink;
use App\Modules\PromotionLinks\Repositories\PromotionLinkRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class PromotionLinkService
{
    private $promotionLinkRepository;

    public function __construct(PromotionLinkRepository $promotionLinkRepository)
    {
        $this->promotionLinkRepository = $promotionLinkRepository;
    }

    public function getAllPromotionLinks($paginateBy = 10){
        return $this->promotionLinkRepository->getAllPaginatedPromotionLinks($paginateBy);
    }

    public function getPromotionLinks(){
        return $this->promotionLinkRepository->getPromotionLinks();
    }

    public function findPromotionLinkByID($id)
    {
        return $this->promotionLinkRepository->findPromotionLinkByID($id);
    }

    public function findPromotionLinkByLinkCode($linkCode)
    {
        return $this->promotionLinkRepository->findPromotionLinkByLinkCode($linkCode);
    }

    public function findOrFailPromotionLinkByLinkCode($linkCode)
    {
        return $this->promotionLinkRepository->findOrFailPromotionLinkByLinkCode($linkCode);
    }

    public function findOrFailPromotionLinkByID($id)
    {
        return $this->promotionLinkRepository->findOrFailPromotionLinkByID($id);
    }

    public function storePromotionLink($validatedPromotionLinkData){
        try{
            DB::beginTransaction();
            $promotionLink = $this->promotionLinkRepository->storePromotionLink($validatedPromotionLinkData);
            DB::commit();
            return $promotionLink;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updatePromotionLink($validated,$id){
        try {
            DB::beginTransaction();
            $promotionLink = $this->findPromotionLinkByID($id);
            $this->validateFileNameAndFile($promotionLink,$validated);
            $this->promotionLinkRepository->update($validated, $promotionLink);
            DB::commit();
            return $promotionLink;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function validateFileNameAndFile($promotionLink,$validatedData){

        if($validatedData['filename'] && isset($validatedData['file'])){
               return true;
        }elseif($validatedData['filename'] && $promotionLink->file){
               return true;
        }elseif(!$validatedData['filename'] && !$promotionLink->file && !isset($validatedData['file'])){
              return true;
        }
        throw new Exception('Both file and file name should be present');
    }


    public function deletePromotionLink($id)
    {
        DB::beginTransaction();
        try {
            $promotionLink = $this->findPromotionLinkByID($id);

            $this->promotionLinkRepository->delete($promotionLink);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $promotionLink;
    }
}
