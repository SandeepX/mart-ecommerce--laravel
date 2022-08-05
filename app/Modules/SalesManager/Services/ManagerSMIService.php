<?php


namespace App\Modules\SalesManager\Services;




use App\Modules\SalesManager\Repositories\ManagerSMILinkRepository;
use App\Modules\SalesManager\Repositories\ManagerSMIRepository;
use App\Modules\SalesManager\Repositories\SocialMedia\SocialMediaRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ManagerSMIService
{

    private $managerSMIRepo;
    private $managerSMILinkRepo;
    private $socialMediaRepository;

    public function __construct(ManagerSMIRepository $managerSMIRepo,
                                ManagerSMILinkRepository $managerSMILinkRepo,
                                SocialMediaRepository $socialMediaRepository
    )
    {
        $this->managerSMIRepo = $managerSMIRepo;
        $this->managerSMILinkRepo = $managerSMILinkRepo;
        $this->socialMediaRepository = $socialMediaRepository;
    }

    public function getAllManagerSMILists()
    {
        return $this->managerSMIRepo->getAllManagerSMI();
    }

    public function findMangerSMIDetailByCode($msmi_Code)
    {
        try{
            $managerSMIDetail = $this->managerSMIRepo->findManagerSMIDetailByCode($msmi_Code);
            if(!$managerSMIDetail){
                throw new Exception('Manager SMI Detail Not Found',404);
            }
            return $managerSMIDetail;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function findManagerSMIDetailByManagerCode($manager_code,$select=[])
    {
        return $this->managerSMIRepo->select($select)
            ->findSMIManagerDetailByManagerCode($manager_code);
    }

    public function storeManagerSMIDetail($managerSMIvalidatedData,$managerSMILinkValidatedData)
    {
        DB::beginTransaction();
        try {
            $managerSMIDetail = $this->managerSMIRepo->findSMIManagerDetailByManagerCode(
                $managerSMIvalidatedData['manager_code']
            );
            if($managerSMIDetail){
                throw new Exception('Data already submitted, please wait for untill verification ',422);
            }
            $managerSMI = $this->managerSMIRepo->store($managerSMIvalidatedData);

            if(!$managerSMI){
                throw new Exception('Opps ! Something went wrong');
            }

            $managerSMILinkData['msmi_code'] = $managerSMI->msmi_code;
            foreach($managerSMILinkValidatedData['social_media'] as $key => $value){
                if($value['links']){
                    $managerSMILinkData['sm_code'] = $value['sm_code'];
                    $socialMedia = $this->socialMediaRepository->select(['base_url','social_media_name'])
                        ->findOrFailSocialMediaByCode($value['sm_code']);
                    foreach($value['links'] as $i =>$link){
                         $this->checkUrl($socialMedia,$link);
                    }
                    $managerSMILinkData['social_media_links'] = json_encode($value['links']);
                    $this->managerSMILinkRepo->store($managerSMILinkData);
                }
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function updateManagerSMIDetail($managerSMILinkValidatedData)
    {
        DB::beginTransaction();
        try {
            $getManagerDetailforUpdate = $this->managerSMIRepo->findSMIManagerDetailByManagerCode(getAuthManagerCode());

            if(!$getManagerDetailforUpdate){
                throw new Exception('Social media influencer detail not found',404);
            }
            if($getManagerDetailforUpdate['is_active'] == 0){
                throw new Exception('Social media influencer  detail is not Active');
            }
            if($getManagerDetailforUpdate->allow_edit == 1 || $getManagerDetailforUpdate->status == 'rejected') {
                $validatedData['allow_edit'] = 0;
                $validatedData['status'] = 'pending';
                $updateManagerSMI = $this->managerSMIRepo->update(
                    $validatedData,$getManagerDetailforUpdate
                );
                if(!$updateManagerSMI){
                    throw new Exception('Opps ! Something went wrong');
                }
                $managerSMILinkData['msmi_code'] = $getManagerDetailforUpdate->msmi_code;
                $managerSMILink = $this->managerSMILinkRepo->getManagerSMILinksByMSMICode(
                    $managerSMILinkData['msmi_code']
                );
                if($managerSMILink){
                    $this->managerSMILinkRepo->deleteManagerSMILinkCollection($managerSMILink);
                }
                foreach($managerSMILinkValidatedData['social_media'] as $key => $value){
                    if($value['links']){
                        $managerSMILinkData['sm_code'] = $value['sm_code'];
                        $socialMedia = $this->socialMediaRepository->select(['base_url','social_media_name'])
                            ->findOrFailSocialMediaByCode($value['sm_code']);
                        foreach($value['links'] as $i =>$link){
                            $this->checkUrl($socialMedia,$link);
                        }
                        $managerSMILinkData['social_media_links'] = json_encode($value['links']);
                        $this->managerSMILinkRepo->store($managerSMILinkData);
                    }
                }
            }else{
                throw new Exception('Permission denied for Update',400);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function toggleStatus($msim_code)
    {
        DB::beginTransaction();
        try{
            $managerSMIDetail = $this->findMangerSMIDetailByCode($msim_code);
            $status = $this->managerSMIRepo->toggleIsActiveStatus($managerSMIDetail);
            DB::commit();
            return $status;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function getManagerSMIDetailByCode($msmi_code,$with=[])
    {
        try{
            $managerSMIDetail = $this->managerSMIRepo
                ->with($with)
                ->getManagerSMIAllDetails($msmi_code);
            if(!$managerSMIDetail){
                throw new Exception('Manager SMI Detail Not Found',404);
            }
            return $managerSMIDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function changeStatus($validatedData,$msmi_code)
    {
        DB::beginTransaction();
        try{
            $managerSMIDetail = $this->findMangerSMIDetailByCode($msmi_code);
            if(!$managerSMIDetail){
                throw new Exception('Manager SMI Detail Not Found',404);
            }
            if($managerSMIDetail->status == $validatedData['status']){
                throw new Exception('Sorry! Change Status operation cannot be performed as it is already in
                            ' .ucfirst($managerSMIDetail->status).' status');
            }
            $this->managerSMIRepo->changeStatus($managerSMIDetail,$validatedData);
            DB::commit();
            return true;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function toggleEditStatus($validatedData,$msim_code)
    {
        DB::beginTransaction();
        try{
            $managerSMIDetail = $this->findMangerSMIDetailByCode($msim_code);
            if($managerSMIDetail['status']=='pending'){
                throw new Exception('Status is in ' .$managerSMIDetail['status'] );
            }
//            $validatedData['status'] = 'pending';
            $editAllow = $this->managerSMIRepo->toggleAllowEditStatus($managerSMIDetail,$validatedData);
            DB::commit();
            return $editAllow;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function checkUrl($socialMedia,$incomingLink)
    {
        if(str_starts_with($incomingLink,$socialMedia->base_url )==false){
            throw new Exception(
                'Invalid base url data for social media '. ucfirst($socialMedia['social_media_name']),
                422 );
        }

        if(str_contains($incomingLink,strtolower($socialMedia->social_media_name)) == false){
            throw new Exception(
                'Invalid Url data for social media '.ucfirst($socialMedia->social_media_name),
                422);
        }

//        /^(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/
        return true;
    }

    private function checkSocialMediaNameInUrl($socialMedia,$incomingLink)
    {
        if(str_contains($incomingLink,strtolower($socialMedia->social_media_name))== false){
            throw new Exception('Invalid Url data for social media '.ucfirst($socialMedia->social_media_name),422);
        }
        return true;
    }

}

