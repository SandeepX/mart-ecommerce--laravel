<?php


namespace App\Modules\Vendor\Services;

use App\Modules\SalesManager\Repositories\SalesManagerRegistrationStatusRepository;
use App\Modules\Vendor\Repositories\VendorTargetIncentiveRepository;
use App\Modules\Vendor\Repositories\VendorTargetRepository;
use App\Modules\Location\Repositories\LocationHierarchyRepository;
use Exception;

class VendorTargetService
{
    private $vendorTargetMasterRepository;
    private $vendorTargetIncentativeRepository;
    private $userRegistrationStatusRepository;
    private $locationHierarchyRepo;

    public function __construct(VendorTargetRepository $vendorTargetMasterRepository,
                                SalesManagerRegistrationStatusRepository $userRegistrationStatusRepository,
                                VendorTargetIncentiveRepository $vendorTargetIncentativeRepository,
                                LocationHierarchyRepository $locationHierarchyRepo
    ){
        $this->vendorTargetMasterRepository = $vendorTargetMasterRepository;
        $this->userRegistrationStatusRepository = $userRegistrationStatusRepository;
        $this->vendorTargetIncentativeRepository = $vendorTargetIncentativeRepository;
        $this->locationHierarchyRepo = $locationHierarchyRepo;
    }

    public function getVendorTargetByVTMCode($VTMcode, $with=[])
    {
        return $this->vendorTargetMasterRepository->getVendorTargetVTMByCode($VTMcode,$with=[]);
    }


    public function storeVendorTargetMasterDetail($vendorTargetData)
    {
        try{
            $vendorTargetData['vendor_code'] = getAuthVendorCode();
            $vendorTargetData['slug'] = makeSlugWithHash($vendorTargetData['name']);
            return $this->vendorTargetMasterRepository->store($vendorTargetData);

        }catch(Exception $exception){
            return $exception;
        }
    }

    public function updateVendorTargetDetail($validatedData,$VTMcode)
    {
        try{
            $vendorTargetDetail = $this->vendorTargetMasterRepository->getVendorTargetVTMByCode($VTMcode);
            $validatedData['vendor_code'] = getAuthVendorCode();
            $validatedData['slug'] = makeSlugWithHash($validatedData['name']);
            $validatedData['updated_by'] = getAuthUserCode();
            $this->vendorTargetMasterRepository->update($vendorTargetDetail,$validatedData);
            return $validatedData;

        }catch(Exception $exception){
            return $exception;
        }
    }

    public function delete($VTMCode)
    {
        try{
            $vendorTargetDetail = $this->vendorTargetMasterRepository->getVendorTargetVTMByCode($VTMCode);
            return $this->vendorTargetMasterRepository->delete($vendorTargetDetail);
        }catch(Exception $exception){
            return $exception;
        }
    }


    public function updateStatusByVTMCode($validatedData)
    {
        try{
            $vendorTargetDetail = $this->vendorTargetMasterRepository->getVendorTargetDetailForAdmin($validatedData['vendorTargetCode']);
            return $this->vendorTargetMasterRepository->updateStatus($vendorTargetDetail,$validatedData['is_active']);

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateVendorTargetStatusByVTMCode($validatedData)
    {
        try{
            $vendorTargetDetail = $this->vendorTargetMasterRepository->getVendorTargetDetailForAdmin($validatedData['vendorTargetCode']);
            return $this->vendorTargetMasterRepository->updateVendorTargetStatus($vendorTargetDetail,$validatedData['status']);

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function showVendorTargetIncentative($VTMcode)
    {
        try{
            return $this->vendorTargetIncentativeRepository->getVendorTargetIncentativeByVTMCode($VTMcode);
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllProvince()
    {
        $html ='';
        try{
            $locations = $this->locationHierarchyRepo->getAllProvince();
            $html.='<option value="">--Select province--</option>';
            foreach ($locations as $key => $detail ) {
                $html.='<option value="'.$detail->location_code.'">'.$detail->location_name.'</option>';
            }
        }catch(Exception $exception){
            throw $exception;
        }
        return $html;
    }

    public function getAllDistrict($provinceCode)
    {
        $html1 ='';
        try{
            $locations = $this->locationHierarchyRepo->getAllDistrict($provinceCode);
            $html1.='<option value="">--Select district--</option>';
            foreach ($locations as $key => $detail ) {
                $html1.='<option value="'.$detail->location_code.'">'.$detail->location_name.'</option>';
            }
        }catch(Exception $exception){
            throw $exception;
        }
        return $html1;
    }

    public function getAllMunicipality($districtCode)
    {
        $html3 ='';
        try{
            $locations = $this->locationHierarchyRepo->getAllMunicipality($districtCode);
            $html3.='<option value="">--Select Municipality--</option>';
            foreach ($locations as $key => $detail ) {
                $html3.='<option value="'.$detail->location_code.'">'.$detail->location_name.'</option>';
            }
        }catch(Exception $exception){
            throw $exception;
        }
        return $html3;
    }

    public function getAllWard($municipalityCode)
    {
        $html3 ='';
        try{
            $locations = $this->locationHierarchyRepo->getAllWard($municipalityCode);
            $html3.='<option value="">--Select Ward--</option>';
            foreach ($locations as $key => $detail ) {
                $html3.='<option value="'.$detail->location_code.'">'.$detail->location_name.'</option>';
            }
        }catch(Exception $exception){
            throw $exception;
        }
        return $html3;
    }



}
