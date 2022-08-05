<?php

namespace App\Modules\ContactMessage\Controllers\Api\Front;

use App\Modules\ContactMessage\Requests\ContactMessageStoreRequest;
use App\Modules\ContactMessage\Services\ContactMessageService;
use App\Http\Controllers\Controller;

use Exception;

class ContactMessageApiController extends Controller
{

    private $contactMessageService;

    public function __construct(ContactMessageService $contactMessageService){

        $this->contactMessageService= $contactMessageService;
    }

    public function storeContactMessage(ContactMessageStoreRequest $request){

        $validatedData = $request->validated();

        try{
            $this->contactMessageService->saveContactMessage($validatedData);
            return sendSuccessResponse('Your message has been sent successfully');
        }catch(Exception $exception){
            return sendErrorResponse([$exception->getMessage()], $exception->getCode());
        }

    }
}
