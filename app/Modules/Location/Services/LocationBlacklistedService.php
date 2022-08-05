<?php


namespace App\Modules\Location\Services;


use App\Modules\Location\Repositories\LocationBlacklistedRepository;
use Illuminate\Support\Facades\DB;

class LocationBlacklistedService
{
    private $blacklistedRepository;

    public function __construct(LocationBlacklistedRepository $blacklistedRepository)
    {
        $this->blacklistedRepository = $blacklistedRepository;
    }

    public function getALlBlacklistedLocation()
    {
        return $this->blacklistedRepository->getAllBlacklistedLocation();
    }

    public function getBlacklistedLocationByLocationCode($locationCode)
    {
        try{
            $locationDetail = $this->blacklistedRepository->getBlackListedLocationByLocationCode($locationCode);
            return $locationDetail;

        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function store($validatedData)
    {
        DB::beginTransaction();
        try{
            $blacklistLocation = $this->blacklistedRepository->store($validatedData);
            DB::commit();
            return $blacklistLocation;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function getBlacklistedLocationByBLHCode($BLHCode)
    {
        try{
            return $this->blacklistedRepository->getBlacklistedLocationByBLHCode($BLHCode);
        }catch(\Exception $exception){
           throw $exception;
        }

    }

    public function update($validatedData ,$BLHCode)
    {
        DB::beginTransaction();
        try{
            $blackListedLocation = $this->getBlacklistedLocationByBLHCode($BLHCode);
            if(!$blackListedLocation){
                throw new \Exception('Blacklisted Location Detail Not Found');
            }
            $updateBlackListedLocation = $this->blacklistedRepository->update($blackListedLocation,$validatedData);
            DB::commit();
            return $updateBlackListedLocation;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeBlacklistedLocationStatus($BLHCode)
    {
        DB::beginTransaction();
        try{
            $blackListedLocation = $this->getBlacklistedLocationByBLHCode($BLHCode);
            if(!$blackListedLocation){
                throw new \Exception('Blacklisted Location Detail Not Found');
            }
            $changeStatus = $this->blacklistedRepository->toggleBlacklistedLocationStatus($blackListedLocation);
            DB::commit();
            return $changeStatus;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function delete($BLHCode)
    {
        DB::beginTransaction();
        try{
            $blackListedLocation = $this->blacklistedRepository->getBlacklistedLocationByBLHCode($BLHCode);
            if(!$blackListedLocation){
                throw new \Exception('Blacklisted Location Detail Not Found');
            }
            $deleteBlackListedLocation = $this->blacklistedRepository->delete($blackListedLocation);
            DB::commit();
            return $deleteBlackListedLocation;

        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
