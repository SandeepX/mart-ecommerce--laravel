<?php

namespace App\Modules\ManagerDiary\Services\VisitClaimRedirection;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimScanRedirection;
use App\Modules\ManagerDiary\Repositories\StoreVisitClaimScanRedirectionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreVisitClaimScanRedirectionService
{
    use ImageService;
    private $storeVisitClaimScanRedirectionRepository;

    public function __construct(StoreVisitClaimScanRedirectionRepository $storeVisitClaimScanRedirectionRepository)
    {
        $this->storeVisitClaimScanRedirectionRepository = $storeVisitClaimScanRedirectionRepository;
    }

    public function findOrFailStoreVisitScanRedirectionByCode($storeVisitClaimScanRedirectionCode,$with =[]){
        return $this->storeVisitClaimScanRedirectionRepository
                        ->findOrFailStoreVisitScanRedirectionByCode($storeVisitClaimScanRedirectionCode,$with);
    }

    public function getAllPaginatedStoreVisitClaimRedirection($paginateBy = 10){
        return $this->storeVisitClaimScanRedirectionRepository->getAllPaginatedStoreVisitClaimRedirection($paginateBy);
    }

    public function storeVisitClaimScanRedirection($validatedData){
        try{
           $authUserCode = getAuthUserCode();
           if(isset($validatedData['image'])){
                $fileNameToStore = $this->storeImageInServer($validatedData['image'], StoreVisitClaimScanRedirection::IMAGE_PATH);
                $validatedData['image'] = $fileNameToStore;
           }
           $validatedData['created_by'] = $authUserCode;
           $validatedData['updated_by'] = $authUserCode;
           DB::beginTransaction();
           $storeVisitClaimScanRedirection = $this->storeVisitClaimScanRedirectionRepository->save($validatedData);
           DB::commit();
           return $storeVisitClaimScanRedirection;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateVisitClaimScanRedirection($visitClaimScanRedirectionCode,$validatedData){
        try{
            $visitClaimScanRedirect = $this->storeVisitClaimScanRedirectionRepository
                                            ->findOrFailStoreVisitScanRedirectionByCode($visitClaimScanRedirectionCode);
            if(isset($validatedData['image'])){
                $this->deleteImageFromServer(StoreVisitClaimScanRedirection::IMAGE_PATH, $visitClaimScanRedirect->image);
                $fileNameToStore = $this->storeImageInServer($validatedData['image'], StoreVisitClaimScanRedirection::IMAGE_PATH);
                $validatedData['image'] = $fileNameToStore;
            }
            $validatedData['updated_by'] = getAuthUserCode();

            DB::beginTransaction();
            $storeVisitClaimScanRedirection = $this->storeVisitClaimScanRedirectionRepository->update($visitClaimScanRedirect,$validatedData);
            DB::commit();
            return $storeVisitClaimScanRedirection;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteScanRedirection($scanRedirectionCode)
    {
        DB::beginTransaction();
        try {
            $scanRedirection = $this->findOrFailStoreVisitScanRedirectionByCode($scanRedirectionCode);
            $this->deleteImageFromServer(StoreVisitClaimScanRedirection::IMAGE_PATH, $scanRedirection->image);
            $this->storeVisitClaimScanRedirectionRepository->delete($scanRedirection);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }



}
