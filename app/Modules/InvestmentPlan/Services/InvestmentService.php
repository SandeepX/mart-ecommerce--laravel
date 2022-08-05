<?php


namespace App\Modules\InvestmentPlan\Services;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\InvestmentPlan\Models\InvestmentPlan;
use App\Modules\InvestmentPlan\Repositories\InvestmentRepository;
use Illuminate\Support\Facades\DB;

class InvestmentService
{
    use ImageService;

    private $investmentRepository;

    public function __construct(InvestmentRepository $investmentRepository)
    {
        $this->investmentRepository = $investmentRepository;
    }

    public function getAllInvestmentPlan()
    {
        try{
            return $this->investmentRepository->getAllInvestmentPlan();

        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function getInvestmentPlanByCode($IPCode)
    {
        try{
            return $this->investmentRepository->findorFailInvestmentPlanByCode($IPCode);
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function getAllActiveInvestmentPlan($with)
    {
        try{
          return $this->investmentRepository->getAllActiveInvestmentPlan($with,$select=[
              'investment_plan_code','name','ip_type_code','image','maturity_period','target_capital','price_start_range','price_end_range','interest_rate'
          ]);
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function getActiveInvestmentPlanDetailByCode($IPCode)
    {
        try{
            $investmentPlan = $this->investmentRepository->getActiveInvestmentPlanByCode($IPCode);
            if(!$investmentPlan){
                throw new \Exception('No Such Investment Plan');
            }
            return $investmentPlan;
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function storeInvestmentPlan($validatedData)
    {
        DB::beginTransaction();
        $filename = '';
        try{
            $image = $validatedData['image'];
            $filename = $this->storeImageInServer($image, InvestmentPlan::IMAGE_PATH);
            $validatedData['image'] = $filename;
            $investmentPlan = $this->investmentRepository->store($validatedData);

            DB::commit();
            return $investmentPlan;

        }catch(\Exception $exception){
            $this->deleteImageFromServer(InvestmentPlan::IMAGE_PATH,$filename);
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateInvestmentPlan($validatedData,$IPCode)
    {
        DB::beginTransaction();
        try{
            $filename = '';
            $newFilename ='';
            if(!isset($validatedData['is_active'])){
                $validatedData['is_active'] = 0;
            }
            $investmentPlanDetail = $this->getInvestmentPlanByCode($IPCode);
            $filename = $investmentPlanDetail['image'];


            if(isset($validatedData['image']) && file_exists($validatedData['image'])) {
                $this->deleteImageFromServer(InvestmentPlan::IMAGE_PATH,$filename);
                $image = $validatedData['image'];
                $newFilename = $this->storeImageInServer($image, InvestmentPlan::IMAGE_PATH);
                $validatedData['image'] = $newFilename;
            }
            $investmentPlan = $this->investmentRepository->update($investmentPlanDetail,$validatedData);

            DB::commit();
            return $investmentPlan;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeInvestmentStatus($IPCode)
    {
        DB::beginTransaction();
        try{
            $investmentPlan = $this->getInvestmentPlanByCode($IPCode);
            $changeInvestmentStatus = $this->investmentRepository->changeInvestmentPlanStatus($investmentPlan);

            DB::commit();
            return $changeInvestmentStatus;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeInvestmentPlanDisplayOrder($sortOrdersToChange)
    {
        try{

            DB::beginTransaction();
            $investmentPlans = $this->investmentRepository->getAllInvestmentPlan();
            foreach ($investmentPlans as $investmentPlan) {
                $investmentPlan->timestamps = false; // To disable update_at field updation
                $id = $investmentPlan->id;

                foreach ($sortOrdersToChange as $order) {
                    if ($order['id'] == $id) {
                        $investmentPlan->update(['sort_order' => $order['position']]);
                    }
                }
            }
            DB::commit();
            return $investmentPlan;
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
