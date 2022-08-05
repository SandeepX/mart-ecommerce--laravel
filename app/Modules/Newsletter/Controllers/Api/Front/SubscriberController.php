<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 10:59 AM
 */

namespace App\Modules\Newsletter\Controllers\Api\Front;


use App\Http\Controllers\Controller;

use App\Modules\Newsletter\Requests\SubscriberStoreRequest;
use App\Modules\Newsletter\Services\SubscriberService;
use Exception;

class SubscriberController extends Controller
{

    private $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    public function storeSubscriber(SubscriberStoreRequest $request){

        try{
            $validated = $request->validated();
            $this->subscriberService->storeSubscriber($validated);
            return sendSuccessResponse('Thank you for joining the newsletter !');
        }catch (Exception $exception){
            return sendErrorResponse([$exception->getMessage()], $exception->getCode());
        }
    }

    public function confirmSubscription($token){

        try{
            $this->subscriberService->confirmSubscription($token);
            return sendSuccessResponse('Thank you! For Subscribing.');
        }catch (Exception $exception){
            return sendErrorResponse([$exception->getMessage()], $exception->getCode());
        }
    }
}