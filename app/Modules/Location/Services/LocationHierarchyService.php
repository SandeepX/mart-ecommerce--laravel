<?php

namespace App\Modules\Location\Services;

use App\Model\LocationHierarchy\LocationHierarchy;
use App\Modules\Location\Repositories\LocationHierarchyRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class LocationHierarchyService
{

    protected $locationHierarchyRepository;
    public function __construct(LocationHierarchyRepository $locationHierarchyRepository)
    {
        $this->locationHierarchyRepository = $locationHierarchyRepository;
    }

    public function getAllLocations(){
        return $this->locationHierarchyRepository->getAllLocations();
    }

    public function getAllLocationsByType($type)
    {
        return $this->locationHierarchyRepository->getAllLocationsByType($type);
      
    }

    public function getLowerLocations( $locationHierarchyCode)
    {
        return $this->locationHierarchyRepository->getLowerLocations($locationHierarchyCode);
    }

    public function getUpperLocation($locationHierarchyCode)
    {
        return $this->locationHierarchyRepository->getUpperLocation($locationHierarchyCode);
    }

    public function getLocationById($locationHierarchyId)
    {
        return $this->locationHierarchyRepository->getLocationById($locationHierarchyId);
    }

    public function getLocationBySlug($locationHierarchySlug)
    {
        return $this->locationHierarchyRepository->getLocationBySlug($locationHierarchySlug);
    }

    public function getLocationByCode($locationHierarchyCode)
    {
        return $this->locationHierarchyRepository->getLocationByCode($locationHierarchyCode);
    }

    public function getLocationPath($locationHierarchyCode,$with=[])
    {
        $locationHierarchy = $this->locationHierarchyRepository->getLocationByCode($locationHierarchyCode,$with);
    
        if ($locationHierarchy->location_type !== 'ward') {
            throw new Exception('the location hierarchy must me ward');
        }

        $locationPath = $this->locationHierarchyRepository->getLocationPath($locationHierarchy);
        return $locationPath;
    }

    public function createLocation($validated){
        try{
            DB::beginTransaction();
            $locationHierarchy = $this->locationHierarchyRepository->create($validated);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
        return $locationHierarchy;
    }

    public function update($validated, $locationHierarchyCode){
        DB::beginTransaction();
        try{

            $locationHierarchy = $this->locationHierarchyRepository->getLocationByCode($locationHierarchyCode);
            if($locationHierarchy->location_type !== 'ward'){
                throw new Exception('Sorry This Location Hierarchy Can not be Updated', 404);
            }
            $locationHierarchy = $this->locationHierarchyRepository->update($validated, $locationHierarchyCode);
            DB::commit();
            return $locationHierarchy;
            
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);

        }
    }

    public function delete($locationHierarchyCode){
        DB::beginTransaction();
        try{

            $locationHierarchy = $this->locationHierarchyRepository->getLocationByCode($locationHierarchyCode);
            if($locationHierarchy->location_type !== 'ward'){
                throw new Exception('Sorry This Location Hierarchy Can not be Deleted', 404);
            }
            $locationHierarchy = $this->locationHierarchyRepository->delete($locationHierarchyCode);
            DB::commit();
            return $locationHierarchy;
            
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);

        }
    }
}
