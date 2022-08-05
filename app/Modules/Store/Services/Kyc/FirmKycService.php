<?php

/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 11:49 AM
 */

namespace App\Modules\Store\Services\Kyc;

use App\Modules\Store\Helpers\FirmKycQueryHelper;
use App\Modules\Store\Models\Kyc\FirmKycBankDetail;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use App\Modules\Store\Repositories\Kyc\FirmKycRepository;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FirmKycService
{

    private $firmKycRepo;

    public function __construct(FirmKycRepository $firmKycRepository)
    {

        $this->firmKycRepo = $firmKycRepository;
    }

    public function findVerifiedFirmKyc($with = [])
    {
        $storeCode = getAuthStoreCode();
        $firmKyc = $this->firmKycRepo->findVerifiedFirmKyc($storeCode, $with);
        return $firmKyc;
    }


    public function findFirmKyc($with = [])
    {
        $storeCode = getAuthStoreCode();
        $firmKyc = $this->firmKycRepo->findFirmKyc($storeCode, $with);
        return $firmKyc;
    }


    public function findOrFailFirmKycEagerByCode($kycCode)
    {
        try {
            $firmKyc = $this->firmKycRepo->findOrFailByCode($kycCode,
                ['store', 'submittedBy', 'respondedBy', 'kycDocuments', 'kycBanksDetail', 'kycBanksDetail.bank']);

            return $firmKyc;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findOrFailFirmKycByCode($kycCode)
    {
        try {
            $firmKyc = $this->firmKycRepo->findOrFailByCode($kycCode);

            return $firmKyc;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getAuthStoreFirmKyc()
    {
        try {

            $storeCode = getAuthStoreCode();
            $firmKyc = $this->firmKycRepo->findOrFailByStoreCode($storeCode, ['kycBanksDetail', 'kycBanksDetail.bank', 'kycDocuments']);

            return $firmKyc;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function saveKyc($validatedData, $userCode, $storeCode)
    {

        try {

            if (!FirmKycQueryHelper::canUpdateKyc($storeCode)) {
                throw new Exception('Firm kyc cannot be updated right now');
            }

            DB::beginTransaction();
            $validatedData['kyc_data']['user_code'] = $userCode;
            $validatedData['kyc_data']['store_code'] = $storeCode;
            $validatedData['kyc_data']['verification_status'] = 'pending';
            $kycMaster = $this->firmKycRepo->save($validatedData['kyc_data']);
            $this->firmKycRepo->saveKycBanksDetail($kycMaster, $validatedData['bank_data']);
            $this->saveFirmKycDocuments($kycMaster, $validatedData['document_data']);
            DB::commit();
            return $kycMaster;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function saveAuthKyc($validatedData)
    {

        try {
            $kyc = $this->saveKyc($validatedData, getAuthUserCode(), getAuthStoreCode());
            return $kyc;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function saveFirmKycDocuments(FirmKycMaster $kycMaster, $validatedDocumentData)
    {

        try {
            if (isset($validatedDocumentData['firm_darta_pramaan_patra'])) {
                $this->firmKycRepo->saveKycDocument($kycMaster, $validatedDocumentData['firm_darta_pramaan_patra'], 'firm_darta_pramaan_patra');
            }
            if (isset($validatedDocumentData['prabhanda_patra'])) {
                $this->firmKycRepo->saveKycDocument($kycMaster, $validatedDocumentData['prabhanda_patra'], 'prabhanda_patra');
            }
            if (isset($validatedDocumentData['niyamaawali'])) {
                $this->firmKycRepo->saveKycDocument($kycMaster, $validatedDocumentData['niyamaawali'], 'niyamaawali');
            }
            if (isset($validatedDocumentData['pan_vat_darta'])) {
                $this->firmKycRepo->saveKycDocument($kycMaster, $validatedDocumentData['pan_vat_darta'], 'pan_vat_darta');
            }
            if (isset($validatedDocumentData['minute'])) {
                $this->firmKycRepo->saveKycDocument($kycMaster, $validatedDocumentData['minute'], 'minute');
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function respondToFirmKycByAdmin($validatedData, $kycCode)
    {
        try {
            $firmKyc = $this->firmKycRepo->findOrFailByCode($kycCode);

            if ($firmKyc->isVerified()) {
                throw new Exception('Following kyc was already verified at ' . $firmKyc->responded_at);
            }


            $validatedData['remarks'] = $validatedData['remarks'] ? $validatedData['remarks'] : null;

            $firmKyc = $this->firmKycRepo->updateVerificationStatus($firmKyc, $validatedData);

            return $firmKyc;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function allowFirmKycUpdateRequest($firmKycCode)
    {
        $kyc = $this->findOrFailFirmKycByCode($firmKycCode);

        if ($kyc->verification_status != 'verified' && !($kyc->can_update_kyc)) {
            throw  new Exception('Kyc must have been verified first to allow this request');
        }
        try {
            DB::beginTransaction();
            $kyc->can_update_kyc = 1;
            $kyc->update_request_allowed_by = getAuthUserCode();
            $kyc->update_request_allowed_at = Carbon::now();
            $kyc->save();

            DB::commit();
            return $kyc;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function getBankDetailsFromIndividualKyc($firmKycCode)
    {
        return FirmKycBankDetail::where('kyc_code', $firmKycCode)->with('bank')->get();
    }
}
