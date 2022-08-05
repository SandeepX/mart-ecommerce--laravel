<?php

namespace App\Modules\Career\Controllers\Web\Admin;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Career\Helpers\CandidateFilter;
use App\Modules\Career\Services\CandidateService;
use App\Modules\Career\Services\CareerService;
use App\Modules\Career\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends BaseController
{
    public $title = 'Candidate';
    public $base_route = 'admin.candidates.';
    public $sub_icon = 'file';
    public $module = 'Career::';

    private $view= 'admin.candidate.';
    private $candidateService;
    private $careerService;
    public function __construct(CandidateService $candidateService,CareerService $careerService)
    {
        $this->candidateService = $candidateService;
        $this->careerService = $careerService;

    }
    public function index(Request $request){
        try{

            $filterParameters = [
                'name' => $request->get('name'),
                'careerCode' => $request->get('careerCode'),
                'appliedFrom' => $request->get('appliedFrom'),
                'appliedTo' => $request->get('appliedTo'),
            ];

            $with =[
                'careers',
            ];
            $career = $this->careerService->getAllCareer();

            $candidates = CandidateFilter::filterPaginatedCandidates($filterParameters,Candidate::RECORDS_PER_PAGE,$with);
//            dd($candidate);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('candidates','filterParameters','career'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }
    public function show($candidateCode){
        try{
            $candidate= $this->candidateService->showCandidateDetail($candidateCode);
            return view(Parent::loadViewData($this->module.$this->view.'show'),compact('candidate'));
        }
        catch(\Exception $exception){
            return redirect()->route($this->base_route.'index')->with('danger',$exception->getMessage());
        }


    }
}
