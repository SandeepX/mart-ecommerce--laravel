<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Requests\StoreDocumentRequest;
use App\Modules\Store\Services\StoreDocumentService;
use App\Modules\Store\Services\StoreService;
use Exception;

class StoreDocumentController extends BaseController
{
    
    public $title = 'Store Document';
    public $base_route = 'admin.stores.documents';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view;
    protected $storeDocumentService, $storeService;

    public function __construct(StoreDocumentService $storeDocumentService, StoreService $storeService)
    {
        $this->middleware('permission:View Store Document List', ['only' => ['index']]);
        $this->middleware('permission:Create Store Document', ['only' => ['create','store']]);
        $this->middleware('permission:Delete Store Document', ['only' => ['destroy']]);

        $this->view = 'admin.store-document.';
        $this->storeDocumentService = $storeDocumentService;
        $this->storeService = $storeService;
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('Store::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($storeSlug)
    {
        try{
            $store = $this->storeService->findOrFailStoreBySlug($storeSlug);
            $storeDocuments = $this->storeDocumentService->getAllDocuments($store);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('store','storeDocuments'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store($storeSlug, StoreDocumentRequest $request)
    {   
        $validated = $request->validated();
        try{
            $store = $this->storeService->findOrFailStoreBySlug($storeSlug);
            $this->storeDocumentService->storeStoreDocuments($validated, $store);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->route($this->base_route.'.create', $store->slug)->with('success', 'Document for Store: '. $store->Store_name .' Created Successfully');
    }

 

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($storeSlug, $storeDocumentName)
    {
        try{
            $store = $this->storeService->findOrFailStoreBySlug($storeSlug);
            $this->storeDocumentService->deleteStoreDocument($storeDocumentName);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->route($this->base_route.'.create', $store->slug)->with('success', 'Document for Store: '. $store->store_name .' Deleted Successfully');
    }
}
