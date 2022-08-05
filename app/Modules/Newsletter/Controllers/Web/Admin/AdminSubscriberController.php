<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 2:45 PM
 */

namespace App\Modules\Newsletter\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;

use App\Modules\Newsletter\Helpers\SubscriberFilter;
use App\Modules\Newsletter\Models\Subscriber;
use App\Modules\Newsletter\Services\SubscriberService;
use Exception;
use Illuminate\Http\Request;

class AdminSubscriberController extends BaseController
{

    public $title = 'Subscriber';
    public $base_route = 'admin.';
    public $sub_icon = 'file';
    public $module = 'Newsletter::';

    private $view='admin.subscribers.';

    private $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        $this->middleware('permission:View Subscriber List', ['only' => ['getSubscribers']]);
        $this->middleware('permission:Update Subscriber Status', ['only' => ['toggleStatus']]);
        $this->middleware('permission:Delete Subscriber', ['only' => ['destroy']]);

        $this->subscriberService = $subscriberService;
    }

    public function getSubscribers(Request $request){
        try{
            $filterParameters = [
                'subscriber' => $request->get('subscriber'),
                'active' => $request->get('active'),
            ];

            //$subscribers = $this->subscriberService->getAllSubscribers();
            $subscribers =SubscriberFilter::filterPaginatedSubscribers($filterParameters,Subscriber::RECORDS_PER_PAGE);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('subscribers','filterParameters'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function toggleStatus($code){

        try{
            $this->subscriberService->updateStatus($code);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function destroy($code){

        try{
            $this->subscriberService->deleteSubscriber($code);
            return redirect()->back()->with('success', $this->title .' deleted successfully');
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }
}