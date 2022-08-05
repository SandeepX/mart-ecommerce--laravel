<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/20/2020
 * Time: 5:41 PM
 */

namespace App\Modules\EnquiryMessage\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;

use App\Modules\EnquiryMessage\Requests\StoreAdminEnquiryMessageRequest;
use App\Modules\EnquiryMessage\Services\EnquiryMessageService;
use Exception;

class EnquiryMessageController extends BaseController
{

    public $title = 'Enquiry Message';
    public $base_route = 'admin.enquiry-messages.';
    public $sub_icon = 'file';
    public $module = 'EnquiryMessage::';

    private $view='admin.enquiry-message.';

    private $enquiryMessageService;

    public function __construct(EnquiryMessageService $enquiryMessageService){

        $this->middleware('permission:View Store Enquiry Message List', ['only' => ['index']]);
        $this->middleware('permission:Show Store Enquiry Message', ['only' => ['show']]);
        $this->middleware('permission:Reply Store Enquiry Message', ['only' => ['reply','storeAdminReplyMessage']]);
        $this->enquiryMessageService = $enquiryMessageService;
    }

    public function index(){
        $enquiryMessages = $this->enquiryMessageService->getAdminInboxMessages();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('enquiryMessages'));
    }

    public function reply($id){
        $enquiryMessage = $this->enquiryMessageService->findOrFailEnquiryMessageById($id);
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('enquiryMessage'));
    }

    public function storeAdminReplyMessage(StoreAdminEnquiryMessageRequest $request){
        try {
            $validated=$request->validated();
            $enquirymessage=$this->enquiryMessageService->storeAdminEnquiryMessageReply($validated);
            return redirect()->back()->with('success','Admin Enquiry Message composed Successfully');        }
        catch (Exception $exception)
        {
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function show($id){
        try{
            $enquiryMessage = $this->enquiryMessageService->findOrFailEnquiryMessageById($id);
            $parent_id=$enquiryMessage->store_message_code;
            $repliedMessages=$this->enquiryMessageService->getEnquiryMessageByCode($parent_id);
            return view(Parent::loadViewData($this->module.$this->view.'show'),compact('enquiryMessage','repliedMessages'));

        }catch (Exception $ex){
            return redirect()->route($this->base_route.'index')->with('danger',$ex->getMessage());
        }
    }


}
