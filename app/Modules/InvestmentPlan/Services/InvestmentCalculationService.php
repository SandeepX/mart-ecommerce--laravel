<?php


namespace App\Modules\InvestmentPlan\Services;

use App\Modules\InvestmentPlan\Repositories\InvestmentRepository;
use Exception;

class InvestmentCalculationService
{
    private $investmentRepository;

    public function __construct(InvestmentRepository $investmentRepository){
        $this->investmentRepository = $investmentRepository;
    }

    public function investmentReturnCalculation($IPCode,$investedAmount)
    {
        try{
            $investmentPlan = $this->investmentRepository->getActiveInvestmentPlanByCode($IPCode);
            if(!$investmentPlan){
                throw new Exception('Investment Plan Not Found !');
            }
            if($investedAmount > $investmentPlan['price_end_range'] || $investedAmount < $investmentPlan['price_start_range']){
                throw new Exception('Please insert amount between price start rang and price end range');
            }
            $investmentPlanData['principle'] = (float)$investedAmount;
            $investmentPlanData['time'] = $investmentPlan['maturity_period']/12;
            $investmentPlanData['rate'] = $investmentPlan['interest_rate'];
            $investmentPlanData['per_unit_share_price'] = $investmentPlan['per_unit_share_price'];
            $investmentPlanData['investment_type_slug'] = $investmentPlan->investmentType->slug;

            if($investmentPlanData['investment_type_slug'] == 'interest'){
                return $this->getInvestmentReturnWhenTypeIsInterest($investmentPlanData);
            }elseif($investmentPlanData['investment_type_slug'] == 'interest-share'){
                return $this->getInvestmentReturnWhenTypeIsInterestShare($investmentPlanData);
            }else{
                return $this->getInvestmentReturnWhenTypeIsPrincipalShare($investmentPlanData);
            }
        }catch (Exception $exception){
            throw $exception;
        }
    }

    private function getInvestmentReturnWhenTypeIsInterest($investmentPlanData)
    {
        try{
            $investmentReturn['interest'] = ($investmentPlanData['principle'] * $investmentPlanData['time'] * $investmentPlanData['rate'])/100;
            $investmentReturn['principle'] = $investmentPlanData['principle'];
            $investmentReturn['share'] = null;
            return $investmentReturn;
        }catch(Exception $ex){
            throw $ex;
        }
    }

    private function getInvestmentReturnWhenTypeIsInterestShare($investmentPlanData)
    {
        try{
            $interestAmount = ($investmentPlanData['principle'] * $investmentPlanData['time'] * $investmentPlanData['rate'])/100;
            $investmentReturn['share'] = $interestAmount / $investmentPlanData['per_unit_share_price'];
            $investmentReturn['principle'] = $investmentPlanData['principle'];
            $data = $this->containsDecimal($investmentReturn['share']);
            if($data){
                $share = intVal($investmentReturn['share']);
                if($share > 0){
                    $remainingBalance = ($interestAmount - $share * $investmentPlanData['per_unit_share_price']);
                }else{
                    $remainingBalance = $interestAmount;
                }
                $investmentReturn['principle'] = $investmentReturn['principle'] + $remainingBalance;
                $investmentReturn['share'] = $share;
            }
            $investmentReturn['interest'] = null;
            return $investmentReturn;
        }catch(Exception $ex){
            throw $ex;
        }
    }

    private function getInvestmentReturnWhenTypeIsPrincipalShare($investmentPlanData)
    {
        try{
            $investmentReturn['interest'] = ($investmentPlanData['principle'] * $investmentPlanData['time'] * $investmentPlanData['rate'])/100;
            $investmentReturn['share'] =  $investmentPlanData['principle'] / $investmentPlanData['per_unit_share_price'];
            $data = $this->containsDecimal($investmentReturn['share']);
            if($data){
                $share = intVal($investmentReturn['share']);
                if($share > 0){
                    $remainingBalance = $investmentPlanData['principle'] - ($share * $investmentPlanData['per_unit_share_price']);
                }else{
                    $remainingBalance = $investmentPlanData['principle'];
                }
                $investmentReturn['interest'] = $investmentReturn['interest'] + $remainingBalance;
                $investmentReturn['share'] = $share;
            }
            $investmentReturn['principle'] = null;
            return $investmentReturn;
        }catch(Exception $ex){
            throw $ex;
        }
    }

    private function containsDecimal($value)
    {
        if ( strpos($value, "." ) !== false ) {
            return true;
        }
        return false;
    }

}
