<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\StockTransfer;

use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransfer;
use App\Modules\AlpasalWarehouse\Requests\StockTransfer\AddProductToStockTransferRequest;
use App\Modules\AlpasalWarehouse\Requests\StockTransfer\StockTransferAddDeliveryDetailRequest;
use App\Modules\AlpasalWarehouse\Requests\StockTransfer\StockTransferReceivedProductQuantityRequest;
use App\Modules\AlpasalWarehouse\Requests\StockTransfer\StockTransferStoreWarehouseRequest;
use App\Modules\AlpasalWarehouse\Services\StockTransfer\WarehouseStockTransferService;
use App\Modules\AlpasalWarehouse\Requests\StockTransfer\StockTransferAddProductDetailsRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Services\ProductService;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;
use Exception;

class WarehouseStockTransferController extends BaseController
{
    protected $title = 'Stock Transfer';
    protected $base_route = 'warehouse.stock-transfer';
    protected $sub_icon = 'file';
    protected $module = 'AlpasalWarehouse::';

    private $view = 'warehouse.warehouse-stock-transfer';
    private $warehouseService;
    private $warehouseStockTransferService;
    private $categoryService;
    private $brandService;
    private $productService;
    private $vendorService;

    const ROWS_PER_PAGE = 10;

    public function __construct(WarehouseService $warehouseService,
                                WarehouseStockTransferService $warehouseStockTransferService,
                                CategoryService $categoryService,
                                BrandService $brandService,
                                ProductService $productService,
                                VendorService $vendorService
    )
    {
        $this->middleware('permission:View WH Stock Transfer List',
            ['only' =>
                'index',
                'getProductsByStockTransferCode',
                'getDeliveryDetail'
            ]
        );
        $this->middleware('permission:Create WH Stock Transfer',
            ['only' =>
                'create',
                'store',
                'addProductsPage',
                'getProductLists',
                'addProductsToTable',
                'addProductsStockTransferDetails',
                'addProductsStockTransferDetailsDraft',
                'deleteStockDetails',
                'addDeliveryDetail'
            ]
        );
        $this->middleware('permission:View Received WH Stock Transfer List',
            ['only' =>
                'warehouseReceivedStocks',
                'getReceivedProductsByStockTransferCode',
                'getReceivedDeliveryDetail'
            ]
        );
        $this->middleware('permission:Update Received WH Stock Transfer Products Quantity', ['only' => 'updateReceivedProductsQuantity']);

        $this->warehouseService = $warehouseService;
        $this->warehouseStockTransferService = $warehouseStockTransferService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->productService = $productService;
        $this->vendorService= $vendorService;
    }

    public function index(Request $request)
    {
        try{
            $filterParameters = [
               'destination_warehouse_name' => $request->get('destination_warehouse_name'),
               'delivery_status' => $request->get('delivery_status'),
               'transaction_date_from' => $request->get('transaction_date_from'),
               'transaction_date_to' => $request->get('transaction_date_to'),
            ];
            $warehouseStockTransfers = $this->warehouseStockTransferService->getAllWarehouseStockTransfer($filterParameters, self::ROWS_PER_PAGE);

            return view(Parent::loadViewData($this->module.$this->view.'.index'),
                compact(
                    'warehouseStockTransfers',
                    'filterParameters'
                ));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        try{
            $warehouses = $this->warehouseService->getOtherWarehouses();
            return view(Parent::loadViewData($this->module.$this->view.'.create'), compact('warehouses'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function store(StockTransferStoreWarehouseRequest $stockTransferRequest)
    {
       try {
           $stockTransferRequestValidated = $stockTransferRequest->validated();
           $warehouseStockTransfer = $this->warehouseStockTransferService->addWarehouseStockTransfer($stockTransferRequestValidated);
           return redirect()->route(
               $this->base_route.'.add-products',
               $warehouseStockTransfer->stock_transfer_master_code
           )->with('success', 'Warehouse added for stock transfer successfully!');
       } catch (Exception $exception) {
           return redirect()->back()->with('danger', $exception->getMessage());
       }

    }

    public function addProductsPage($stockTransferCode)
    {
        try {
            //$vendors = $this->vendorService->getAllActiveVendors();
            $vendors = $this->vendorService->getWarehouseWiseVendors(getAuthWarehouseCode());
            $categories = $this->categoryService->getCategoryMaster(['category_code', 'category_name']);
            $brands = $this->brandService->getAllBrands();
            $warehouseStockTransfer = $this->warehouseStockTransferService
                ->getWarehouseStockTransferByCode(
                    $stockTransferCode,
                    'destinationWarehouses',
                    ['stock_transfer_master_code', 'destination_warehouse_code']
                );

            $warehouseStockTransferProducts = $this->warehouseStockTransferService->getWarehouseStockTransferProductsDetails($stockTransferCode);

            return view(Parent::loadViewData(
                $this->module.$this->view.'.add-products'),
                compact(
                    'stockTransferCode',
                    'warehouseStockTransfer',
                    'warehouseStockTransferProducts',
                    'vendors',
                    'categories',
                    'brands'
                ));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getProductLists(Request $request, $stockTransferCode)
    {
        try{
            $response = [];
            $filterParameters = [
                'vendor_name' => $request->get('vendor_name'),
                'category_names' => $request->get('category_names'),
                'brand_name' => $request->get('brand_name'),
                'product_name' => $request->get('product_name')
            ];
            $warehouseProducts = $this->warehouseStockTransferService->getWarehouseProducts($filterParameters, 25);
            //dd($warehouseProducts);
            $response['html'] = view($this->module.$this->view.'.partials.includes.products-table',
                compact('warehouseProducts'))->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function addProductsToTable(Request $request, $stockTransferCode)
    {
        try{
            $response = [];
            $product = $this->warehouseStockTransferService->getProductByWarehouseProductMasterCode($request->warehouse_product_master_code, $stockTransferCode);
            $warehouse_product_master_code = $request->warehouse_product_master_code;
            $response['html'] = view($this->module.$this->view.'.partials.includes.product-row', compact('product', 'warehouse_product_master_code'))->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function addProductsStockTransferDetails(StockTransferAddProductDetailsRequest $request, $stockTransferCode)
    {
        try {
            $data = $request->except('_token');
            $array = [];
            for($key = 0; $key < count($data['warehouse_product_master_code']); $key++) {
                $array[] = [
                        'stock_transfer_details_code' => $data['stock_transfer_details_code'][$key],
                        'warehouse_product_master_code' => $data['warehouse_product_master_code'][$key],
                        'product_quantity' => $data['product_quantity'][$key],
                    ];
            }
            $this->warehouseStockTransferService->storeStockTransferProductsDetails($array, $stockTransferCode, 'sent');
            $request->session()->flash('success', 'Products sent successfully!');
            return redirect()->route($this->base_route.'.index');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function addProductsStockTransferDetailsDraft(StockTransferAddProductDetailsRequest $request, $stockTransferCode)
    {


        try {
            $data = $request->except('_token');
            $array = [];
            for($key = 0; $key < count($data['warehouse_product_master_code']); $key++) {
                $array[] = [
                    'stock_transfer_details_code' => $data['stock_transfer_details_code'][$key],
                    'warehouse_product_master_code' => $data['warehouse_product_master_code'][$key],
                    'product_quantity' => $data['product_quantity'][$key],
                ];
            }
            $response['data'] = $this->warehouseStockTransferService->storeStockTransferProductsDetails($array, $stockTransferCode);

            return response()->json($response);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getProductsByStockTransferCode(Request $request, $stockTransferCode)
    {

        try{
            $filterParameters = [
                'product_name' => $request->get('product_name'),
                'variant_name' => $request->get('variant_name'),
                'price_condition' => $request->get('price_condition'),
                'total_price' => $request->get('total_price'),
            ];
            $priceConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $warehouseStockTransfer = $this->warehouseStockTransferService
                ->getWarehouseStockTransferByCode(
                    $stockTransferCode,
                    'destinationWarehouses',
                    ['stock_transfer_master_code', 'destination_warehouse_code']
                );
            $warehouse = $this->warehouseService->findWarehouseByCode($warehouseStockTransfer->destination_warehouse_code);
            $warehouseStockTransferProductsByCode = $this->warehouseStockTransferService->getWarehouseStockTransferProductsDetails($stockTransferCode, $filterParameters, self::ROWS_PER_PAGE);
           // dd($warehouseStockTransferProductsByCode);
//            foreach($warehouseStockTransferProductsByCode as $product)
//            {
//                $productPackages = ProductUnitPackagingHelper::getAvailableProductPackagingTypes($product->product_code,$product->product_variant_code);
//                $product['package_details'] = collect($productPackages)->map(function($product){
//                            return $product;
//                });
//                foreach($product['package_details'] as $package)
//                {
//                    if($package['package_code'] === $product->package_code)
//                    {
//                         $product->unit_rate = $package['unit_rate'];
//                    }
//                }
//            }
            return view(
                Parent::loadViewData($this->module.$this->view.'.wh-stock-transfer-products-list'),
                compact(
                    'warehouseStockTransferProductsByCode',
                    'warehouse',
                    'stockTransferCode',
                    'filterParameters',
                    'priceConditions'
                ));

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function warehouseReceivedStocks(Request $request)
    {
        try{
            $filterParameters = [
                'source_warehouse_name' => $request->get('source_warehouse_name'),
                'delivery_status' => $request->get('delivery_status'),
                'transaction_date_from' => $request->get('transaction_date_from'),
                'transaction_date_to' => $request->get('transaction_date_to'),
            ];
            $warehouseStockTransfers = $this->warehouseStockTransferService->getAllReceivedWarehouseStockTransfers($filterParameters, self::ROWS_PER_PAGE);
            return view(Parent::loadViewData($this->module.$this->view.'.wh-stock-transfer-received'),
                compact(
                    'filterParameters',
                    'warehouseStockTransfers'
                ));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getReceivedProductsByStockTransferCode(Request $request, $stockTransferCode)
    {
        try{
            $filterParameters = [
                'product_name' => $request->get('product_name'),
                'variant_name' => $request->get('variant_name'),
                'price_condition' => $request->get('price_condition'),
                'total_price' => $request->get('total_price'),
            ];
            $priceConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $warehouseStockTransfer = $this->warehouseStockTransferService
                ->getWarehouseStockTransferByCode(
                    $stockTransferCode,
                    'sourceWarehouses',
                    ['stock_transfer_master_code', 'source_warehouse_code', 'status']
                );
            $warehouse = $this->warehouseService->findWarehouseByCode($warehouseStockTransfer->source_warehouse_code);
            $warehouseStockTransferProductsByCode = $this->warehouseStockTransferService->getWarehouseStockTransferProductsDetails( $stockTransferCode, $filterParameters, self::ROWS_PER_PAGE );
           // dd($warehouseStockTransferProductsByCode);

            return view(
                Parent::loadViewData($this->module.$this->view.'.wh-stock-transfer-received-products-lists'),
                compact(
                    'warehouseStockTransferProductsByCode',
                    'warehouseStockTransfer',
                    'warehouse',
                    'stockTransferCode',
                    'filterParameters',
                    'priceConditions'
                ));

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updateReceivedProductsQuantity(StockTransferReceivedProductQuantityRequest $request, $stockTransferCode)
    {
        try{
            $validatedData = $request->validated();
            $this->warehouseStockTransferService->updateWarehouseReceivedProductsQuantity($validatedData, $stockTransferCode);
            return redirect()->back()->with('success', 'Products quantity updated successfully!');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function deleteStockDetails(Request $request, $stockTransferCode)
    {
        try{
            $response = [];
            $response['data'] = $this->warehouseStockTransferService->deleteWarehouseStockTransferDetailsByCode($request->get('stock_transfer_details_code'), $stockTransferCode);
            return response()->json($response);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function addDeliveryDetail(StockTransferAddDeliveryDetailRequest $request, $stockTransferCode)
    {
        try{
            $response = [];
            $stockTransferMeta =  $this->warehouseStockTransferService->addStockTransferMasterMeta($request, $stockTransferCode);
            $response['html'] = view($this->module.$this->view.'.partials.includes.added-stock-transfer-meta', compact('stockTransferMeta'))->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

    }

    public function getDeliveryDetail($stockTransferCode)
    {
        try{
            $getStockTransferDeliveryStatus = WarehouseStockTransfer::select('status')->where('stock_transfer_master_code', $stockTransferCode)->firstOrFail();
            $stockTransferDeliveryStatus = $getStockTransferDeliveryStatus->status;
            $warehouseStockTransferDeliveryDetail = $this->warehouseStockTransferService->getStockTransferMetaByStockTransferCode($stockTransferCode);
            return view(Parent::loadViewData($this->module.$this->view.'.wh-stock-transfer-delivery-detail'),
                compact(
                    'warehouseStockTransferDeliveryDetail',
                    'stockTransferCode',
                    'stockTransferDeliveryStatus'
                    ));
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
    public function getReceivedDeliveryDetail($stockTransferCode)
    {
        try{
            $warehouseStockTransferDeliveryDetail = $this->warehouseStockTransferService->getStockTransferMetaByStockTransferCode($stockTransferCode);
            return view(Parent::loadViewData($this->module.$this->view.'.wh-stock-transfer-received-delivery-detail'),
                compact(
                    'warehouseStockTransferDeliveryDetail',
                    'stockTransferCode'
                    ));
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function addProductToStockTransfer(AddProductToStockTransferRequest $request,$stockTransferCode)
    {
       // dd(1);
        try {
            $validatedStockTransfer = $request->validated();
            $stockTransfer = $this->warehouseStockTransferService->addProductToStockTransfer($validatedStockTransfer,$stockTransferCode,'sent');
            $request->session()->flash('success', 'Products sent successfully!');
            return redirect()->route($this->base_route.'.index');
        } catch (Exception $exception) {
            return $exception->getMessage();

        }
    }

    public function addProductToStockTransferDraft(AddProductToStockTransferRequest $request,$stockTransferCode)
    {
        try {
            $validatedStockTransfer = $request->validated();
            $stockTransfer = $this->warehouseStockTransferService->addProductToStockTransfer($validatedStockTransfer,$stockTransferCode);
            $request->session()->flash('success', 'Products saved as draft successfully!');
            return redirect()->route($this->base_route.'.index');
        } catch (Exception $exception) {
            return $exception->getMessage();

        }
    }
}
