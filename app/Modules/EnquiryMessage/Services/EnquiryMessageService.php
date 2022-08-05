<?php

namespace App\Modules\EnquiryMessage\Services;

use App\Modules\EnquiryMessage\Repositories\EnquiryMessageRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class EnquiryMessageService
{
    private $enquiryMessageRepository;
    public function __construct(
        EnquiryMessageRepository $enquiryMessageRepository
    ) {
        $this->enquiryMessageRepository = $enquiryMessageRepository;
    }
    public  function findOrFailEnquiryMessageById($id){

        $enquiryMessage= $this->enquiryMessageRepository->findOrFailById($id);
        return $enquiryMessage;
    }
    public  function getEnquiryMessageByCode($parent_id){

        $enquiryMessage= $this->enquiryMessageRepository->getEnquiryMessageByCode($parent_id);
        return $enquiryMessage;
    }
    public  function searchMailbox($data,$paginatedBy){
        $receiver_code = getAuthUserCode();
        $enquiryMessages= $this->enquiryMessageRepository->searchMailbox($data,$receiver_code,$paginatedBy);
        return $enquiryMessages;
    }
    public  function searchMailboxSentMessage($data,$paginatedBy){
        $sender_code = getAuthUserCode();
        $enquiryMessages= $this->enquiryMessageRepository->searchMailboxSentMessage($data,$sender_code,$paginatedBy);
        return $enquiryMessages;
    }
    public function storeEnquiryMessage($validated)
    {
        try {
            DB::beginTransaction();
            $enquiryMessage = $this->enquiryMessageRepository->composeEnquiryMessage($validated);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $enquiryMessage;


    }
    public function storeEnquiryMessageReply($validated,$parent_id)
    {


        try {
            DB::beginTransaction();
            $enquiryMessage = $this->enquiryMessageRepository->composeEnquiryMessageReply($validated,$parent_id);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $enquiryMessage;


    }
    public function storeAdminEnquiryMessageReply($validated)
    {


        try {
            DB::beginTransaction();
            $enquiryMessage = $this->enquiryMessageRepository->storeAdminEnquiryMessageReply($validated);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $enquiryMessage;


    }
    public function getInboxMessages($paginatedBy)
    {
        $receiver_code = getAuthUserCode();
//        dd($receiver_code);
        $enquiryMessages = $this->enquiryMessageRepository->getInboxMessages($receiver_code,$paginatedBy);
        return $enquiryMessages;

    }
    public function getSentMessages($paginatedBy)
    {
        $sender_code = getAuthUserCode();
        $enquiryMessages = $this->enquiryMessageRepository->getSentMessages($sender_code,$paginatedBy);
        return $enquiryMessages;

    }
    public function getRepliedMessages($store_message_code)
    {
        $enquiryMessage=$this->enquiryMessageRepository->findOrFailByCode($store_message_code);
        $parent_id=$enquiryMessage->parent_id;
        $enquiryMessages = $this->enquiryMessageRepository->getRepliedMessages($store_message_code,$parent_id);
        $enquiryMessagess['enquiry_message']=$enquiryMessage;
        $enquiryMessagess['enquiry_replied_message']=$enquiryMessages;
        return $enquiryMessagess;

    }

    public function getAdminInboxMessages()
    {
        $receiver_code = getAuthStoreCode();
        $enquiryMessages = $this->enquiryMessageRepository->getAdminInboxMessages($receiver_code);
        return $enquiryMessages;

    }

    public function getAllMessageOfStore($userCode)
    {
        return $this->enquiryMessageRepository->getAllMessageOfStore($userCode);
    }
    public function getAdminSentMessages()
    {
        $sender_code = getAuthStoreCode();

        $enquiryMessages = $this->enquiryMessageRepository->getAdminSentMessages($sender_code);
        return $enquiryMessages;

    }
    public function updateInboxMessageSeen($storeMessageCode)
    {
        $receiver_code = getAuthUserCode();
//        dd($receiver_code);
        $enquiryMessages = $this->enquiryMessageRepository->updateInboxMessageSeen($receiver_code,$storeMessageCode);
        return $enquiryMessages;

    }
    public function getParentMessage($parentId)
    {
        $sender_code = getAuthUserCode();
//        dd($receiver_code);
        $enquiryMessages = $this->enquiryMessageRepository->getParentMessage($sender_code,$parentId);
        return $enquiryMessages;

    }

    public function getSentMessageDetail($storeMessageCode)
    {
        $enquiryMessage = $this->enquiryMessageRepository->getSentMessageDetail($storeMessageCode);
        return $enquiryMessage;

    }
}
