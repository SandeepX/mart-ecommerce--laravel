<?php


namespace App\Modules\Impersonate\Controllers\Admin\Api;
use App\Modules\Impersonate\Services\ImpersonateService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Resources\MinimalStoreResource;
use App\Modules\Store\Resources\StoreAccountStatusResource;
use App\Modules\User\Resources\MinimalUserResource;
use Illuminate\Http\Request;


class ImpersonateController
{
    public $impersonateService;

    public function __construct(ImpersonateService $impersonateService)
    {
       $this->impersonateService = $impersonateService;
    }


    public function checkUUID(Request $request)
    {
        try{
            $validatedData['uuid'] = $request->uuid;
            $data = $this->impersonateService->verifyUUID($validatedData);
            $user = $data->user;
            $token = $user->createToken('impersonate',['view-only'])->accessToken;

            $tokenData['token_type'] = 'Bearer';
            $tokenData['access_token'] = $token;

            $connectedWarehouses = [];

            $connectedWarehouse = StoreWarehouseHelper::getFirstConnectedWarehouse($data->store_code);
            if($connectedWarehouse){
                array_push($connectedWarehouses,[
                    'warehouse_name'=>$connectedWarehouse->warehouse_name,
                    'warehouse_code'=>$connectedWarehouse->warehouse_code
                ]);
            }

            return sendSuccessResponse(
                'Authenticated',
                [
                    'user' => new MinimalUserResource($user),
                    'store_details' => new MinimalStoreResource($user->store),
                    'account_status' => new StoreAccountStatusResource($user->store),
                    'connected_warehouses'=> $connectedWarehouses,
                    'tokens' => $tokenData,
                    'impersonate' => true
                ]
            );
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
