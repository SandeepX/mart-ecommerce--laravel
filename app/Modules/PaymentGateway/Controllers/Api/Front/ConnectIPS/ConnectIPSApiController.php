<?php

namespace App\Modules\PaymentGateway\Controllers\Api\Front\ConnectIPS;

use App\Exceptions\Custom\ConnectIpsPaymentException;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\Models\Payment;
use App\Modules\PaymentGateway\Requests\ConnectIpsPaymentRequest;
use App\Modules\PaymentGateway\Services\ConnectIpsService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Modules\PaymentGateway\core\PaymentGatewayResponse;

use Exception;

class ConnectIPSApiController extends Controller
{

    public $module = 'PaymentGateway::';
    public $view = 'admin.connect-ips.';

    private $connectIpsService;

    public function __construct(ConnectIpsService $connectIpsService)
    {
        $this->connectIpsService = $connectIpsService;
    }

    public function paymentStore(ConnectIpsPaymentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            //dd(Carbon::now()->toDateString());
            $onlinePaymentMaster = $this->connectIpsService->processIpsPayment($validatedData);
            $ipsApiRequestData = json_decode($onlinePaymentMaster->request);
            return sendSuccessResponse('Ips payment proceeded successfully', $ipsApiRequestData);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }


    public function validatePayment($transactionId)
    {
        try {
            $onlinePaymentMaster = $this->connectIpsService->validateIpsPayment($transactionId);

            $ipsApiResponseData = json_decode($onlinePaymentMaster->response);
            $ipsApiResponseData->txnAmt = convertPaisaToRs($ipsApiResponseData->txnAmt);
            if ($onlinePaymentMaster->status == 'rejected') {
                throw new Exception('Payment transaction failed.');
            }

            return sendSuccessResponse('Payment Successfull', $ipsApiResponseData);

        } catch (Exception $exception) {
            if ($exception instanceof ConnectIpsPaymentException) {
                return sendErrorResponse($exception->getMessage(), 403, $exception->getData());
            }
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
