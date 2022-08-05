<?php


namespace App\Modules\SMSProcessor\Controllers\Web;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\SMSProcessor\Helpers\SmsFilterHelper;
use App\Modules\SMSProcessor\Services\SMSService;
use Illuminate\Http\Request;
Use Exception;


class SmsController extends BaseController
{
    public $title = 'SMS';
    public $base_route = 'admin.sms';
    public $sub_icon = 'file';
    public $module = 'SMSProcessor::';
    public $view = 'sms.';

    private $smsService;

    public function __construct(SMSService $smsService){
        $this->smsService = $smsService;
    }

    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'sms_code' =>$request->get('smsCode'),
                'purpose' => $request->get('purpose'),
                'created_to' => $request->get('created_to'),
                'created_from'=> $request->get('created_from'),
            ];

            $smsDetail = SmsFilterHelper::getAllSmsByFilter($filterParameters,$with=['balanceMasterDetail']);
            return view(Parent::loadViewData($this->module.$this->view.'sms-log-detail'),compact('smsDetail','filterParameters'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

}
