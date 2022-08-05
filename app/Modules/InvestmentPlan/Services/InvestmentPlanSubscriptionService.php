<?php


namespace App\Modules\InvestmentPlan\Services;

use App\Exceptions\Custom\ConnectIpsPaymentException;
use App\Modules\InvestmentPlan\Events\UpdateInvestmentPlanSubscriptionPaymentStatusEvent;
use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use App\Modules\InvestmentPlan\Repositories\InvestmentSubscriptionRepository;
use App\Modules\InvestmentPlan\Repositories\InvestmentInterestReleaseRepository;
use App\Modules\InvestmentPlan\Services\InvestmentPayment\InvestmentOfflinePaymentService;
use App\Modules\OfflinePayment\Events\OfflinePaymentEvent;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentRepository;
use App\Modules\PaymentGateway\Repositories\OnlinePaymentMasterRepository;
use App\Modules\PaymentProcessor\Classes\ConnectIPS;
use App\Modules\SalesManager\Repositories\ManagerRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\PaymentGateway\Services\ConnectIpsService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class InvestmentPlanSubscriptionService
{
    private $investmentSubscriptionRepo;
    private $investmentInterestReleaseRepo;
    private $userRepo;
    private $connectIpsService;
    private $investmentOfflinePaymentService;
    private $managerRepository;
    private $offlinePaymentRepository;
    private $onlinePaymentRepository;

    public function __construct(InvestmentSubscriptionRepository $investmentSubscriptionRepo,
                                InvestmentInterestReleaseRepository $investmentInterestReleaseRepo,
                                UserRepository $userRepo,
                                ConnectIpsService $connectIpsService,
                                InvestmentOfflinePaymentService $investmentOfflinePaymentService,
                                ManagerRepository $managerRepository,
                                OfflinePaymentRepository $offlinePaymentRepository,
                                OnlinePaymentMasterRepository $onlinePaymentRepository
    ){
        $this->investmentSubscriptionRepo = $investmentSubscriptionRepo;
        $this->investmentInterestReleaseRepo = $investmentInterestReleaseRepo;
        $this->userRepo = $userRepo ;
        $this->connectIpsService = $connectIpsService;
        $this->investmentOfflinePaymentService = $investmentOfflinePaymentService;
        $this->managerRepository = $managerRepository;
        $this->offlinePaymentRepository = $offlinePaymentRepository;
        $this->onlinePaymentRepository = $onlinePaymentRepository;
    }

    public function getAllSubscribedInvestmentPlan()
    {
        return $this->investmentSubscriptionRepo->getAllSubscribedIP();
    }

    public function findInvesmentPlanSubcriptionByCode($ipsCode,$with= [])
    {
        return $this->investmentSubscriptionRepo->findActiveInvestmentPlanSubscription($ipsCode,$with);
    }

    public function findOrFailInvestmentPlanSubscription($ipsCode,$with=[]){
       return $this->investmentSubscriptionRepo->findOrFailInvestmentPlanSubscription($ipsCode,$with);
    }

    public function storeOnlineSubscription($data)
    {
        DB::beginTransaction();
        try{
            $investmentPlanSubscription = $this->storeSubscription($data);
            $data['ip_subscription_code'] = $investmentPlanSubscription->ip_subscription_code;
            $ipsPayData['amount'] = $data['amount'];
            $ipsPayData['transaction_type'] = $data['transaction_type'];
            $ipsPayData['initiator_code'] = $investmentPlanSubscription->investment_holder_id;
            $ipsPayData['payment_initiator'] =  $investmentPlanSubscription->investment_plan_holder;
            $ipsPayData['reference_code'] = $investmentPlanSubscription->ip_subscription_code;
            $onlinePayForInvestment = $this->connectIpsService->pay($ipsPayData);


            $this->investmentSubscriptionRepo->update(
                                    [ 'payment_code' => $onlinePayForInvestment->online_payment_master_code],
                                    $investmentPlanSubscription
                                 );

            DB::commit();
            return  $onlinePayForInvestment;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function storeOfflineSubscription($data){
        try{
            DB::beginTransaction();
            $investmentPlanSubscription = $this->storeSubscription($data);
            $data['ip_subscription_code'] = $investmentPlanSubscription->ip_subscription_code;
            $offlinePaymentForInvestment = $this->investmentOfflinePaymentService->saveOfflinePaymentForInvestmentSubscription($investmentPlanSubscription,$data);
            $this->investmentSubscriptionRepo->update(
                    [
                        'payment_code' => $offlinePaymentForInvestment->offline_payment_code
                    ],
                    $investmentPlanSubscription
            );
            DB::commit();
            return $offlinePaymentForInvestment;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function storeSubscription($data){
        try{
            $getUserDetail = $this->userRepo->findUserByCode(getAuthUserCode());
            if(!$getUserDetail){
                throw new Exception('User detail not found !');
            }
            if($data['referred_by']){
                $getReferralUserCode = $this->managerRepository->findManagerCodeByReferralCode($data['referred_by'],$select=['user_code']);
                if($getReferralUserCode){
                    $data['referred_by'] = $getReferralUserCode['manager_code'];
                }else{
                    $data['referred_by'] = NULL;
                }
            }

            $investmentPlanInterestRelease = $this->investmentInterestReleaseRepo
                ->findOrFailActiveInvestmentInterestReleaseByIPCode($data);

            if(!$investmentPlanInterestRelease){
                throw new Exception('Investment Plan Not Available !',404);
            }

            if($data['amount'] < $investmentPlanInterestRelease->investmentPlan->price_start_range ||
                $data['amount'] > $investmentPlanInterestRelease->investmentPlan->price_end_range
            ){
                throw new Exception('Please insert amount between price start rang and price end range',400);
            }

            $validatedData['investment_plan_holder'] = $getUserDetail->userType->namespace;
            //    $validatedData['investment_holder_type'] = basename($validatedData['investment_plan_holder']);
            $validatedData['investment_holder_type'] = strtolower(substr($validatedData['investment_plan_holder'], (strrpos( $validatedData['investment_plan_holder'],'\\') + 1)));
            $validatedData['investment_holder_id'] = getHolderId($validatedData['investment_plan_holder']);
            $validatedData['investment_plan_code'] = $investmentPlanInterestRelease->investmentPlan->investment_plan_code;
            $validatedData['investment_plan_name'] = $investmentPlanInterestRelease->investmentPlan->name;
            $validatedData['maturity_period'] = $investmentPlanInterestRelease->investmentPlan->maturity_period;
            $validatedData['ipir_option_code'] = $data['ipir_option_code'];
            $validatedData['interest_rate'] = $investmentPlanInterestRelease->investmentPlan->interest_rate;
            $validatedData['price_start_range'] = $investmentPlanInterestRelease->investmentPlan->price_start_range;
            $validatedData['price_end_range'] = $investmentPlanInterestRelease->investmentPlan->price_end_range;
            $matureDate  =  Carbon::now()->addMonths($validatedData['maturity_period']);
            $validatedData['maturity_date'] = date_format( $matureDate,"Y-m-d");
            $validatedData['referred_by'] =  $data['referred_by'];
            $validatedData['invested_amount'] = $data['amount'];
            $validatedData['payment_mode'] = isset($data['payment_mode']) ? $data['payment_mode'] : 'online';
            $investmentPlanSubscription = $this->investmentSubscriptionRepo->store($validatedData);
            return $investmentPlanSubscription;
        }catch (Exception $exception){
            throw $exception;
        }
    }


    public function getAllSubscribedInvestmentPlanByUser($userCode)
    {
        try{
            $getUserDetail = $this->userRepo->findUserByCode(getAuthUserCode());
            if(!$getUserDetail){
                throw new Exception('User detail not found !');
            }
            $investmentPlanHolder = $getUserDetail->userType->namespace;
            //$validatedData['investment_holder_type'] = basename($investmentPlanHolder);
            $validatedData['investment_holder_type'] = substr($investmentPlanHolder,(strrpos($investmentPlanHolder,'\\') + 1));
            $validatedData['investment_holder_id'] = getHolderId($investmentPlanHolder);

            $subscribedInvestmentPlan = $this->investmentSubscriptionRepo->getSubscribedInvestmentPlanByHolderIdAndType($validatedData);
            return $subscribedInvestmentPlan;
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function getALlSubscribedInvestmentReferredByManager($referredCode)
    {
        try{
            return $this->investmentSubscriptionRepo->getALlSubscribedInvestmentReferredByManager($referredCode);
        }catch(Exception $exception){
            throw $exception;
        }

    }

    public function respondIS($validatedData,$ISCode)
    {
        DB::beginTransaction();
        try {
            $subscriptionData = $this->investmentSubscriptionRepo->findOrFailInvestmentPlanSubscription($ISCode);
            if($subscriptionData->admin_status != 'pending'){
              throw new Exception('While Responding Status should be pending');
            }
            if($subscriptionData->payment_mode == 'online'){
                $onlinePaymentCode = $subscriptionData->payment_code;
                $onlinePayment = $this->validateIPSPaymentForInvestment($onlinePaymentCode);
                if($onlinePayment->status == 'verified' && $validatedData['admin_status'] == 'rejected'){
                    throw new Exception('Could not reject it since the '.$onlinePayment->digitalWallet->wallet_name.' payment is already verified.
                                            Please contact administration for further information :(');
                }
                if($validatedData['admin_status'] == 'accepted' && $onlinePayment->status == 'rejected'){
                    $validatedData['admin_status'] = 'rejected';
                }
                event(new UpdateInvestmentPlanSubscriptionPaymentStatusEvent($onlinePayment,$validatedData));
            }elseif($subscriptionData->payment_mode== 'offline'){

                if($validatedData['admin_status'] == 'accepted'
                    && !array_key_exists('balance_reconciliation_code',$validatedData)
                    && empty($validatedData['balance_reconciliation_code'])
                ){
                    throw new Exception('Balance Reconciliation Code is required!');
                }

                $validatedData['verification_status'] = ($validatedData['admin_status'] == 'accepted') ? 'verified' : $validatedData['admin_status'];
                $validatedData['remarks'] = $validatedData['admin_remark'];
                $offlinePaymentData = $this->offlinePaymentRepository->findOrFailByCode($subscriptionData->payment_code);
                if($offlinePaymentData->verification_status !=  'pending'){
                    throw new Exception('While Responding Status should be pending');
                }
                $offlinePaymentData = $this->offlinePaymentRepository->updateVerificationStatus($offlinePaymentData,$validatedData);
                event(new OfflinePaymentEvent($offlinePaymentData,$subscriptionData,$validatedData));
            }else{
                throw new Exception('Payment mode not found for this subscription');
            }
            DB::commit();
            return $subscriptionData;
        } catch (Exception $exception) {
            DB::rollBack();
            throw ($exception);
        }
    }

    private function validateIPSPaymentForInvestment($onlinePaymentCode){
        try{
            $onlinePaymentMaster = $this->onlinePaymentRepository->findOrFailByOnlinePaymentCode($onlinePaymentCode);
            if ($onlinePaymentMaster->status == 'verified'){
                throw new ConnectIpsPaymentException('Payment already verified.',['amount'=>convertPaisaToRs($onlinePaymentMaster->amount)]);
            }
            if ($onlinePaymentMaster->status == 'rejected'){
                throw new ConnectIpsPaymentException('Payment was rejected.',['amount'=>convertPaisaToRs($onlinePaymentMaster->amount)]);
            }

            $connectIps = new ConnectIPS($onlinePaymentMaster->transaction_id,$onlinePaymentMaster->amount);
            $response = $connectIps->validatePayment();
            $response= json_decode($response);
            $currentDateTime = Carbon::now();
            $validatedData['response'] = json_encode($response);
            $validatedData['response_at'] = $currentDateTime;

            if ($response->status == 'SUCCESS'){
                $validatedData['status'] = 'verified';
            }
            else{
                $validatedData['status'] = 'rejected';
            }
            return $this->onlinePaymentRepository->updateOnlinePayment($onlinePaymentMaster,$validatedData);
        }catch (Exception $exception){
            throw $exception;
        }
    }

//    private function onlinePay(InvestmentPlanSubscription $investmentPlanSubscription,$data)
//    {
//        DB::beginTransaction();
//        try{
//
//            DB::commit();
//            return $onlinePayForInvestment;
//        }catch(Exception $exception){
//            DB::rollBack();
//            throw $exception;
//        }
//    }

    public function changeInvestmentSubscriptionStatus($ISCode)
    {
        try{
            $subscriptionData = $this->investmentSubscriptionRepo->findActiveInvestmentPlanSubscription($ISCode);
            if(!$subscriptionData){
                throw new Exception('Investment Plan Subscritpion detail not found !');
            }
            DB::beginTransaction();
            $subscriptionStatus = $this->investmentSubscriptionRepo->changeInvestmentSubscriptionStatus($subscriptionData);
            DB::commit();
            return $subscriptionStatus;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}

