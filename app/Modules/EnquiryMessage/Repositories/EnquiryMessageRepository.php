<?php


namespace App\Modules\EnquiryMessage\Repositories;

use App\Modules\EnquiryMessage\Models\EnquiryMessage;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class EnquiryMessageRepository
{

    public function composeEnquiryMessage($validated){

        $validated['sender_code']=getAuthUserCode();
        $validated['receiver_code']="U00000001";

       return EnquiryMessage::create($validated)->fresh();

    }
    public  function findOrFailById($id){

        $enquiryMessage = EnquiryMessage::where('id',$id)->first();

        if (!$enquiryMessage){
            throw new ModelNotFoundException('Enquiry message not found for the id');
        }

        return $enquiryMessage;
    }
    public  function getEnquiryMessageByCode($parent_id){

        $enquiryMessages = EnquiryMessage::where('parent_id',$parent_id)->orderBy('created_at','DESC')->get();

        if (!$enquiryMessages){
            throw new ModelNotFoundException('Enquiry message not found for the Code');
        }

        return $enquiryMessages;
    }
    public function composeEnquiryMessageReply($validated,$parent_id){
        $enquiryMessage=EnquiryMessage::where('parent_id',$parent_id)->first();
        //store Image
//        $validated['bank_logo'] = $this->storeImageInServer($validated['bank_logo'], 'uploads/banks');
//        $validated['slug'] = make_slug($validated['bank_name']);
//        dd($validated);
        $validated['sender_code']=getAuthUserCode();
        $validated['subject']=$enquiryMessage->subject;
        $validated['department']=$enquiryMessage->department;

        $validated['parent_id']=$parent_id;
//        $validated['receiver_code']=getAuthUserCode();
        $validated['receiver_code']="U00000001";
//        dd($validated);
//        dd(auth()->user());
        return EnquiryMessage::create($validated)->fresh();

    }

    public function getInboxMessages($receiver_code,$paginatedBy=5)
    {

       $enquiryMessages = EnquiryMessage::whereIn('id',function($query) use ($receiver_code)
       {
           $query->select(DB::raw('MAX(id)'))
               ->from('store_message')
               ->whereRaw("store_message.receiver_code = '$receiver_code'")->groupBy('parent_id');
       })->latest()->paginate($paginatedBy);
      // dd($enquiryMessages);
        return  $enquiryMessages;

    }

    public function getSentMessages($sender_code,$paginatedBy=5)
    {
        $parent_id = null;

        $enquiryMessages = EnquiryMessage::where('sender_code',$sender_code)->where('parent_id',$parent_id)->orderBy('created_at','DESC')->paginate($paginatedBy);
        return $enquiryMessages;
    }
    public function findOrFailByCode($store_message_code)
    {

        $enquiryMessage = EnquiryMessage::findOrFail($store_message_code);
        return $enquiryMessage;
    }
    public function getRepliedMessages($store_message_code,$parent_code)
    {
//        $parentMessage=EnquiryMessage::where('store_message_code',$parent_code)->first();
        $enquiryMessages = EnquiryMessage::where('parent_id',$parent_code)->get();
        return $enquiryMessages;
    }
    public function getAdminInboxMessages($receiver_code,$with=[])
    {
//        dd($receiver_code);
        $parent_id = null;
        $enquiryMessages = EnquiryMessage::where('parent_id',$parent_id)->orderBy('created_at','DESC')->get();
//        dd($enquiryMessages);
        return $enquiryMessages;
    }

    public function getAllMessageOfStore($userCode)
    {
        return EnquiryMessage::where('sender_code',$userCode)->orWhere('receiver_code',$userCode)->get();
    }

//    public function getAdminSentMessages($sender_code)
//    {
////        dd($receiver_code);
//        $parent_id = null;
//        $enquiryMessages = EnquiryMessage::where('sender_code',$sender_code)->where('parent_id',$parent_id)->paginate(10);
////        dd($enquiryMessages);
//        return $enquiryMessages;
//    }
    public function storeAdminEnquiryMessageReply($validated){

        $validated['sender_code']=getAuthUserCode();

        return EnquiryMessage::create($validated)->fresh();

    }
    public function searchMailbox($data,$receiver_code,$paginatedBy=5)
    {
       $enquiryMessages=EnquiryMessage::where('receiver_code',$receiver_code)
           ->where(function($query) use ($data)
           {
               $query->where('subject','like','%'.$data.'%')
                   ->orWhere('department','like','%'.$data.'%')
                   ->orWhere('message','like','%'.$data.'%');
           })
           ->groupBy('parent_id')
           ->latest()->paginate($paginatedBy);
        return $enquiryMessages;
    }
    public function searchMailboxSentMessage($data,$sender_code,$paginatedBy=5)
    {
        $enquiryMessages=EnquiryMessage::where('sender_code',$sender_code)
            ->where(function($query) use ($data)
            {
                $query->where('subject','like','%'.$data.'%')
                    ->orWhere('department','like','%'.$data.'%')
                    ->orWhere('message','like','%'.$data.'%');
            })
            ->groupBy('parent_id')
            ->latest()->paginate($paginatedBy);
        return $enquiryMessages;
    }
    public function updateInboxMessageSeen($receiver_code,$storeMessageCode)
    {

//        dd($receiver_code);
        $enquiryMessage = EnquiryMessage::where('store_message_code',$storeMessageCode)->first();
        $enquiryMessage->is_seen=1;
        $enquiryMessage->save();
        return $enquiryMessage;
    }
    public function getParentMessage($sender_code,$parentId)
    {

        $enquiryMessage = EnquiryMessage::where('store_message_code',$parentId)->where('sender_code',$sender_code)->first();
        return $enquiryMessage;
    }

    public function getSentMessageDetail($storeMessageCode)
    {

        $enquiryMessage = EnquiryMessage::where('store_message_code',$storeMessageCode)->get();
        return $enquiryMessage;
    }
}
