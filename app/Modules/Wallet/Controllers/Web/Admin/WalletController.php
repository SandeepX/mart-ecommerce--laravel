<?php


namespace App\Modules\Wallet\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Wallet\Helpers\WalletHelper;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Services\WalletService;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class WalletController extends BaseController
{
    public $title = 'Wallets';
    public $base_route = 'admin.wallets';
    public $sub_icon = 'file';
    public $module = 'Wallet::';
    private $view = 'wallets.';

    private $walletService;

    public function __construct(WalletService $walletService){
        $this->middleware('permission:View Wallet Lists', ['only' => ['index']]);

        $this->walletService = $walletService;
    }

    public function index(Request $request){
        try{
            $filterParameters = [
                'wallet_name' => $request->get('wallet_name'),
                'wallet_type' => $request->get('wallet_type'),
                'current_balance_order' =>$request->get('current_balance_order')
            ];


             $with = [
                 'walletable',
                 'getLatestTransaction'=>function($q){
                    $q->orderBy('created_at','DESC');
                 }
             ];
             $wallets = WalletHelper::filterPaginatedWallets($filterParameters,Wallet::PAGINATE_BY,$with);

             $wallets->getCollection()->transform(function ($wallet,$key){
                     $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);
                 return $wallet;
             });

             $walletTypes = Wallet::WALLET_TYPE;

            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('wallets','filterParameters','walletTypes'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }



}
