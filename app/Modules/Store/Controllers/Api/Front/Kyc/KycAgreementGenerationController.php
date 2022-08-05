<?php

namespace App\Modules\Store\Controllers\Api\Front\Kyc;


use App\Http\Controllers\Controller;
use App\Modules\Store\Resources\Kyc\FirmKycResource;
use App\Modules\Store\Resources\Kyc\IndividualKycResource;
use App\Modules\Store\Services\Kyc\KycAgreementGenerationService;
use App\Modules\Store\Transformers\FirmKycDetailTransformer;
use App\Modules\Store\Transformers\IndividualKycDetailTransformer;
use PDF;
use Exception;

class KycAgreementGenerationController extends Controller
{

    private $kycAgreementGenerationService;

    public function __construct(
        KycAgreementGenerationService $kycAgreementGenerationService
    ) {
        $this->kycAgreementGenerationService = $kycAgreementGenerationService;
    }

    public function generateAkhtiyariAgreementPaper()
    {

       // return $this->kycAgreementGenerationService->akhtiyariPatraGenerationCondition();

        $data = [
            'name' => "सुमिना डंगोल",
            'title' => ' dxfgu/kflnsf j8f g+= !) gof jfg]Zj',
            'heading' => 'Allpasal - Akhityari Agreement Paper'
        ];

       // $pdf = PDF::loadView("Store::kyc.downloads.akhtiyari-agreement-paper", $data);
        //return $pdf->stream('akhtiyari-agreement-paper-allpasal-' . time() . '-' . uniqueHash(30) . '.pdf');

        try {
          $condition =$this->kycAgreementGenerationService->akhtiyariPatraGenerationConditionForAuthStore();

          if (!$condition['verified']){
              throw new Exception($condition['message']);
          }

          $sanchalakKyc = $condition['sanchalakKyc'];
          $akhtiyariKyc = $condition['akhtiyariKyc'];
          $firmKyc = $condition['firmKyc'];

          $sanchalakKyc = (new IndividualKycDetailTransformer($sanchalakKyc))->transform();
          $akhtiyariKyc = (new IndividualKycDetailTransformer($akhtiyariKyc))->transform();
          $firmKyc = (new FirmKycDetailTransformer($firmKyc))->transform();



          $data = [
              'sanchalakKyc'=> [
                  'citizenship' => [
                      'name' => $sanchalakKyc['kyc_citizenship_detail']['citizenship_full_name'],
                      'number' => $sanchalakKyc['kyc_citizenship_detail']['citizenship_no'],
                      'issued_date' => $sanchalakKyc['kyc_citizenship_detail']['citizenship_issued_date'],
                      'district' => $sanchalakKyc['kyc_citizenship_detail']['citizenship_district'],
                      'municipality' =>$sanchalakKyc['kyc_citizenship_detail']['citizenship_municipality'],
                      'ward_no' =>$sanchalakKyc['kyc_citizenship_detail']['citizenship_ward_no'],

                  ]
              ],
              'firm_kyc' =>[
                'business_name' => $firmKyc['business_name'],
                'business_registered_from' => config('kyc_information_transformation.business_registered_from.'.$firmKyc['business_registered_from']),
                'store_location_tree' => $firmKyc['store_location_tree'],
                'business_registration_no' =>convertEnglishNumToNepali($firmKyc['business_registration_no']),
                'business_pan_vat_type' => config('kyc_information_transformation.business_tax_type.'.$firmKyc['business_pan_vat_type']),
                'business_pan_vat_number' => convertEnglishNumToNepali($firmKyc['business_pan_vat_number'])


            ],
              'akhtiyariKyc'=>  [

                'citizenship' => [
                    'name' => $akhtiyariKyc['kyc_citizenship_detail']['citizenship_full_name'],
                    'number' => $akhtiyariKyc['kyc_citizenship_detail']['citizenship_no'],
                    'issued_date' => $akhtiyariKyc['kyc_citizenship_detail']['citizenship_issued_date'],
                    'district' => $akhtiyariKyc['kyc_citizenship_detail']['citizenship_district'],
                    'municipality' =>$akhtiyariKyc['kyc_citizenship_detail']['citizenship_municipality'],
                    'ward_no' =>$akhtiyariKyc['kyc_citizenship_detail']['citizenship_ward_no'],

                ]
            ],

          ];

          return sendSuccessResponse('Data Found',$data);

            // $pdf = PDF::loadView("Store::kyc.downloads.akhtiyari-agreement-paper", $data);
            // return $pdf->download('akhtiyari-agreement-paper-allpasal-' . time() . '-' . uniqueHash() . '.pdf');


        } catch (Exception $ex) {
            return sendErrorResponse($ex->getMessage(), $ex->getCode());
        }
    }


    public function generateSamjhautaAgreementPaper()
    {

       // return view("Store::kyc.downloads.samjhauta-agreement-paper");
        try {
            $condition =$this->kycAgreementGenerationService->samjhautaPatraGenerationConditionForAuthStore();

            if (!$condition['verified']){
                throw new Exception($condition['message'],400);
            }

            $individualKyc = $condition['sanchalakKyc'];
            $firmKyc = $condition['firmKyc'];

            $individualKyc = (new IndividualKycDetailTransformer($individualKyc))->transform();
            $firmKyc = (new FirmKycDetailTransformer($firmKyc))->transform();

            $data = [
                'individual_kyc'=> [
                    'name_in_devanagari' => $individualKyc['name_in_devanagari']
                ],
                'firm_kyc' =>[
                    'business_name' => $firmKyc['business_name'],
                    'store_location_tree' => $firmKyc['store_location_tree']
                ],
            ];

            return sendSuccessResponse('Data Found',$data);

            // $pdf = PDF::loadView("Store::kyc.downloads.samjhauta-agreement-paper", $data);
            // return $pdf->download('samjhauta-agreement-paper-allpasal-' . time() . '-' . uniqueHash() . '.pdf');

        } catch (Exception $ex) {
            return sendErrorResponse($ex->getMessage(), $ex->getCode());
        }
    }
}
