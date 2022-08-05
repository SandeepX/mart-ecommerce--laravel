<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 1:47 PM
 */

namespace App\Modules\Store\Repositories\Payment;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Package\Models\PackageType;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;

use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Models\Payments\StoreLoadBalanceDetail;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentMeta;

use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentDocument;
use App\Modules\Store\Repositories\StoreRepository;
use Carbon\Carbon;
use Exception;

class StoreMiscellaneousPaymentRepository
{

    use ImageService;
    public $storeRepo;

    public function __construct(StoreRepository $storeRepo) {
        $this->storeRepo=$storeRepo;

    }

    public function getAllByStoreCodeWith($storeCode,array $with){

        return StoreMiscellaneousPayment::with($with)->where('store_code',$storeCode)->latest()->get();
    }

    public function getAllMiscPaymentsByStoreCodeAndPaymentType($storeCode,$paymentFor,array $with){

        return StoreMiscellaneousPayment::with($with)
            ->where('store_code',$storeCode)
            ->where('payment_for',$paymentFor)
            ->latest()
            ->get();
        //dd(StoreMiscellaneousPayment::with($with)->where('user_code',$userCode)->where('payment_for',$paymentFor)->get());
    }

    public function checkLatestInitialRegistrationByVerificationStatus($storeCode,$verificationStatus){
        $initialRegMiscPayment = StoreMiscellaneousPayment::where('store_code',$storeCode)
                                                          ->where('payment_for','initial_registration')
                                                          ->where('verification_status',$verificationStatus)
                                                          ->latest()
                                                         ->first();
        return $initialRegMiscPayment ? true : false;
    }

    public function findOrFailByCodeOfStore($miscPaymentCode,$storeCode,$with=[]){

        return StoreMiscellaneousPayment::with($with)->where('store_misc_payment_code',$miscPaymentCode)
            ->where('store_code',$storeCode)->firstOrFail();
    }

    public function findLatestMiscPaymentByTypeAndStore($miscPaymentType,$storeCode,$with=[]){

        return StoreMiscellaneousPayment::with($with)->where('payment_for',$miscPaymentType)
            ->where('store_code',$storeCode)->latest()->first();
    }
    public function findOrFailByCode($miscPaymentCode,$with=[]){

        return StoreMiscellaneousPayment::with($with)->where('store_misc_payment_code',$miscPaymentCode)->firstOrFail();
    }

    public function updateVerificationStatus(StoreMiscellaneousPayment $storeMiscellaneousPayment,$validatedData){

        $storeMiscellaneousPayment->verification_status = $validatedData['verification_status'];
        $storeMiscellaneousPayment->remarks = $validatedData['remarks'];
        $storeMiscellaneousPayment->responded_by = getAuthUserCode();
        $storeMiscellaneousPayment->responded_at = Carbon::now();
        $storeMiscellaneousPayment->questions_checked_meta = isset($validatedData['questions_checked_meta']) ? $validatedData['questions_checked_meta'] : NULL;

        $storeMiscellaneousPayment->save();

        return $storeMiscellaneousPayment;
    }

    public function updateVerificationStatusForInitialRegistration(StoreMiscellaneousPayment $storeMiscellaneousPayment,$validatedData)
    {
        $storeMiscellaneousPayment->verification_status = $validatedData['verification_status'];
        $storeMiscellaneousPayment->remarks = $validatedData['remarks'];
        $storeMiscellaneousPayment->responded_by = getAuthUserCode();
        $storeMiscellaneousPayment->responded_at = Carbon::now();

        $storeMiscellaneousPayment->save();

//        if($validatedData['verification_status']=='verified'){
//            $store = $this->storeRepo->updateStatus($storeMiscellaneousPayment->store_code,'approved');
//        }
        return $storeMiscellaneousPayment;
    }
    public function save($validatedData){
        return StoreMiscellaneousPayment::create($validatedData)->fresh();

    }



    public function saveTransaction($validatedDataTransaction){
        return StoreBalanceMaster::create($validatedDataTransaction);
    }

    public function saveLoadBalanceDetail($StoreLoadbalance){
        return StoreLoadBalanceDetail::create($StoreLoadbalance)->fresh();
    }


    public function savePaymentMetaDetail(StoreMiscellaneousPayment $miscellaneousPayment,$metaDetails)
    {
        try{
            $miscellaneousPayment->paymentMetaData()->createMany($metaDetails);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function savePaymentDocument(StoreMiscellaneousPayment $miscellaneousPayment,$document,$documentType){

        $fileNameToStore='';
        try{

            //dd($miscellaneousPayment->paymentDocuments);
            $fileNameToStore = $this->storeImageInServer($document, StoreMiscellaneousPaymentDocument::UPLOAD_PATH);

            $miscellaneousPayment->paymentDocuments()->create([
                'file_name' => $fileNameToStore,
                'document_type' => $documentType

            ]);

        }catch (Exception $e){

            $this->deleteImageFromServer(StoreMiscellaneousPaymentDocument::UPLOAD_PATH,$fileNameToStore);
            throw $e;
        }
    }


    public function getAllMiscPayment($miscData)
    {
       $storeMiscPayment = StoreMiscellaneousPayment::where('store_code',$miscData['store_code'])
                                ->where('payment_for',$miscData['payment_for'] )
                                ->where('deposited_by',$miscData['deposited_by'] )
                                ->where('transaction_date',$miscData['transaction_date'])
                                ->where('amount',$miscData['amount'])
                                ->where('verification_status',$miscData['verification_status'])
                                ->where('payment_type',$miscData['payment_type'])
                                ->get();
       return $storeMiscPayment;
    }

    public function getLatestMiscPaymentVerificationStatus($miscData)
    {
        $storeMiscPayment = StoreMiscellaneousPayment::where('store_code',$miscData['store_code'])
                            ->where('payment_for',$miscData['payment_for'] )
                            ->where('verification_status',$miscData['verification_status'])
                            ->where('payment_type',$miscData['payment_type'])
                            ->orderBy('created_at','DESC')
                            ->first();

        return $storeMiscPayment;
    }

    //for investment
    public function getLatestMiscPaymentVerificationStatusByUserCode($miscData)
    {
        $storeMiscPayment = StoreMiscellaneousPayment::where('user_code',$miscData['user_code'])
                            ->where('payment_for',$miscData['payment_for'] )
                            ->where('verification_status',$miscData['verification_status'])
                            ->where('payment_type',$miscData['payment_type'])
                            ->orderBy('created_at','DESC')
                            ->first();

        return $storeMiscPayment;
    }

    public function getPaymentDetail($SMPcode)
    {
        $paymentDetailForLoadBalanceVerification = StoreMiscellaneousPaymentMeta::where('store_misc_payment_code',$SMPcode)->get();
        return $paymentDetailForLoadBalanceVerification;
    }

    public function getPaymentAdminDescriptionMetaDetail($SMPCode,$select)
    {
        $paymentDetailForLoadBalanceVerification = StoreMiscellaneousPaymentMeta::select($select)
            ->where('store_misc_payment_code',$SMPCode)
            ->where('key','admin_description')
            ->first();
        return $paymentDetailForLoadBalanceVerification;
    }

    public function updateStorePayment(StoreMiscellaneousPayment $storePayment,$validatedData){
        $storePayment = $storePayment->update($validatedData);
        return $storePayment;
    }

    public function findOrFailPaymentMetaByCode($paymentMetaCode){
        return StoreMiscellaneousPaymentMeta::where('payment_meta_code',$paymentMetaCode)->firstOrFail();
    }

    public function updatePaymentMetaDetails(StoreMiscellaneousPaymentMeta $storePaymentMeta,$validatedData)
    {
      $storePaymentMeta = $storePaymentMeta->update($validatedData);
      return $storePaymentMeta;
    }

    public function createPaymentMetaDetails($validatedData)
    {
      $storePaymentMeta = StoreMiscellaneousPaymentMeta::create($validatedData)->fresh();
      return $storePaymentMeta;
    }

    public function getBalanceMiscPaymentForMatching($miscPaymentData){


        $description = explode(' ',strip_tags($miscPaymentData['description']));

       //dd($miscPaymentData);


        $miscPaymentDetail = StoreMiscellaneousPayment::where(function ($query) use ($miscPaymentData){

                                                          if($miscPaymentData['payment_method']=='bank')
                                                          {
                                                           $query->whereHas('paymentMetaData',function ($query) use ($miscPaymentData){
                                                             $query->where('key','bank_code')->where('value',$miscPaymentData['payment_body_code']);
                                                           });
                                                          }
                                                          if($miscPaymentData['payment_method'] =='remit')
                                                          {
                                                            $query->whereHas('paymentMetaData',function ($query) use ($miscPaymentData){
                                                                $query->where('key','remit_code')->where('value',$miscPaymentData['payment_body_code']);
                                                            });
                                                          }

                                                          if($miscPaymentData['payment_method'] =='digital_wallet')
                                                          {
                                                             $query->whereHas('paymentMetaData',function ($query) use ($miscPaymentData){
                                                                $query->where('key','wallet_code')->where('value',$miscPaymentData['payment_body_code']);
                                                             });
                                                          }
                                                     })
                                                    ->where('transaction_date',$miscPaymentData['transaction_date'])
                                                    ->where('amount',$miscPaymentData['transaction_amount'])
                                                    ->where('payment_for','load_balance')
                                                    ->where('verification_status','pending')
                                                    ->where('has_matched',0)
                                                    ->where(function ($query) use ($miscPaymentData,$description){
                                                           $query->whereIn('deposited_by',$description);
                                                           $query->orWhereIn('contact_phone_no',$description);
                                                           $query->orWhereHas('paymentMetaData',function ($query) use ($miscPaymentData,$description){
                                                               $query->where('key','remark')->whereIn('value',$description);
                                                           });
                                                           $query->orWhereHas('paymentMetaData',function ($query) use ($miscPaymentData,$description){
                                                               $query->where('key','cheque_no')->where('value',$description);
                                                           });
                                                           $query->orWhereHas('paymentMetaData',function ($query) use ($miscPaymentData,$description){

                                                              $query->where('key','transaction_number')->where('value',$description);
                                                           });
                                                    })
                                                    ->orderBy('created_at','ASC')
                                                    ->first();
                                return $miscPaymentDetail;
    }

    public function updateHasMatched($storePayment)
    {
        return $storePayment->update([
            'has_matched' => 1,
        ]);
    }

}
