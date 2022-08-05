<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/6/2020
 * Time: 1:48 PM
 */

namespace App\Modules\EnquiryMessage\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\EnquiryMessage\Requests\StoreEnquiryMessageReplyRequest;
use App\Modules\EnquiryMessage\Requests\StoreEnquiryMessageRequest;
use App\Modules\EnquiryMessage\Resources\EnquiryMessageCollection;
use App\Modules\EnquiryMessage\Resources\EnquiryMessageInboxCollection;
use App\Modules\EnquiryMessage\Resources\EnquiryMessageResource;
use App\Modules\EnquiryMessage\Resources\EnquiryMessageSingleInboxResource;
use App\Modules\EnquiryMessage\Services\EnquiryMessageService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class EnquiryMessageFrontController extends Controller
{
    private $enquiryMessageService;

    public function __construct(EnquiryMessageService $enquiryMessageService)
    {
        $this->enquiryMessageService = $enquiryMessageService;
    }

      //store enquiry message
    public function storeEnquiryMessage(StoreEnquiryMessageRequest $request)
    {

//        return $request->all();

        try {
            $validated=$request->validated();
            $enquirymessage=$this->enquiryMessageService->storeEnquiryMessage($validated);
            return sendSuccessResponse('Enquiry Message sent',$enquirymessage);
        }
        catch (Exception $exception)
        {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }


public function getInboxMessages(Request $request)
{
    try{
        $paginatedBy=$request->get('rows_per_page');
        $enquiryMessages = $this->enquiryMessageService->getInboxMessages($paginatedBy);
        return new EnquiryMessageInboxCollection($enquiryMessages);
//        return sendSuccessResponse('Data Found',  $enquiryMessages);
    }catch(\Exception $exception){
        return sendErrorResponse($exception->getMessage(), 400);
    }
}
    public function getSentMessages(Request $request)
    {
        try{
            $paginatedBy=$request->get('rows_per_page');
            $enquiryMessage = $this->enquiryMessageService->getSentMessages($paginatedBy);

            return new EnquiryMessageInboxCollection($enquiryMessage);
//            return sendSuccessResponse('Data Found',  $enquiryMessages);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    //store enquiry message
    public function storeEnquiryMessageReply(StoreEnquiryMessageReplyRequest $request,$parent_id)
    {


        try {
            $validated=$request->validated();
            $enquirymessage=$this->enquiryMessageService->storeEnquiryMessageReply($validated,$parent_id);
            return sendSuccessResponse('Enquiry Message sent',$enquirymessage);
        }
        catch (Exception $exception)
        {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public  function  getRepliedMessages($store_message_code)
    {

        try{
            $enquiryMessages = $this->enquiryMessageService->getRepliedMessages($store_message_code);
            $enquiryRepliedMessages= new EnquiryMessageCollection($enquiryMessages['enquiry_replied_message']);
            $enquiryMessage=new EnquiryMessageSingleInboxResource($enquiryMessages['enquiry_message']);
            return sendSuccessResponse('Data Found',  [
                'enquiry_message'=>$enquiryMessage,
                'enquiry_replied_messages'=>$enquiryRepliedMessages,

            ]);

        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function searchMailbox(Request $request)
    {

        try {
            $data=$request->get('search');
            $paginatedBy=$request->get('rows_per_page');
            $enquiryMessages=$this->enquiryMessageService->searchMailbox($data,$paginatedBy);
            return new EnquiryMessageCollection($enquiryMessages);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function searchMailboxSentMessage(Request $request)
    {

        try {
            $data=$request->get('search');
            $paginatedBy=$request->get('rows_per_page');
            $enquiryMessages=$this->enquiryMessageService->searchMailboxSentMessage($data,$paginatedBy);
            return new EnquiryMessageCollection($enquiryMessages);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function updateInboxMessageSeen($storeMessageCode)
    {
        try{
            $enquiryMessages = $this->enquiryMessageService->updateInboxMessageSeen($storeMessageCode);
        return sendSuccessResponse('Message has been seen',  $enquiryMessages);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function getParentMessage($parentId)
    {
        try{
            $parentMessage = $this->enquiryMessageService->getParentMessage($parentId);
            return new EnquiryMessageResource($parentMessage);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

//    detail of sent Message

    public function getSentMessageDetail($storeMessageCode)
    {
        try{
            $enquiryMessage = $this->enquiryMessageService->getSentMessageDetail($storeMessageCode);

            return new EnquiryMessageInboxCollection($enquiryMessage);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
