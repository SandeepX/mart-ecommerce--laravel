<?php


namespace App\Modules\Impersonate\Services;


use App\Modules\Impersonate\Repositories\ImpersonateRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\User\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImpersonateService
{
    public $impersonateRepo;
    public $storeRepository;
    public $userRepo;

    public function __construct(ImpersonateRepository $impersonateRepo,
                                StoreRepository $storeRepository,
                                UserRepository $userRepo
    )
    {
        $this->impersonateRepo = $impersonateRepo;
        $this->storeRepository = $storeRepository;
        $this->userRepo = $userRepo;
    }

    public function store($storeCode)
    {
       try{
           $storeDetail = $this->storeRepository->findOrFailStoreByCode($storeCode);

           $userDetail = $this->userRepo->findUserByCode(getAuthUserCode());
           $loginInform = array();
           if($userDetail){
               $loginInform['name'] = $userDetail->name;
               $loginInform['login_email'] = $userDetail->login_email;
               $loginInform['last_login_ip'] = $userDetail->last_login_ip;
               $loginInform['last_login_at'] = $userDetail->last_login_at;
           }
           $impersonateData['impersonater_code'] = getAuthUserCode();
           $impersonateData['impersonatee_type'] = get_class($storeDetail);
           $impersonateData['impersonatee_code'] = $storeDetail['store_code'];
           $impersonateData['uuid'] =  Str::uuid()->toString();
           $impersonateData['remark'] = 'imperonater- ' .getAuthUserCode() . ', impersonatee- ' .  $impersonateData['impersonatee_code'];
           $impersonateData['logged_in_details'] = json_encode($loginInform);
           $impersonateData['logged_in_at'] =   Carbon::now('Asia/Kathmandu')->format('Y-m-d H:i:s');;
           $impersonateData['logged_out_at'] = '';
           $impersonateData['expires_at'] =  Carbon::now('Asia/Kathmandu')->addHour(1)->format('Y-m-d H:i:s');; //expires after 1 hour

           DB::beginTransaction();
                $impersonate = $this->impersonateRepo->store($impersonateData);
                DB::commit();
            return $impersonateData['uuid'];

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function verifyUUID($validatedData)
    {
        try{
            $impersonateDetail = $this->impersonateRepo->getImpersonateDataByUUID($validatedData);
            if(!$impersonateDetail){
                throw new Exception('Invalid token',401);
            }
            if($impersonateDetail){
                $currentDateTime =  Carbon::now('Asia/Kathmandu')->format('Y-m-d H:i:s');
                if($currentDateTime <= $impersonateDetail['expires_at']){
                    $storeDetail = $this->storeRepository->findOrFailStoreByCode($impersonateDetail['impersonatee_code']);
                    return $storeDetail;
                }else{
                    throw new Exception('Token Expired',401);
                }
            }

        }catch(\Exception $exception){
            throw $exception;
        }
    }

}
