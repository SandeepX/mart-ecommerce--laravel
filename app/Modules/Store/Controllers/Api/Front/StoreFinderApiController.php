<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/6/2020
 * Time: 1:48 PM
 */

namespace App\Modules\Store\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\Store\Helpers\StoreFilter;
use App\Modules\Store\Helpers\StoreLocationFinder;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Resources\StoreFinderApiCollection;
use App\Modules\Store\Resources\StoreFinderApiResource;
use http\Env\Response;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreFinderApiController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function findStoresInLocation(Request $request)
    {
        try{

            $province = $request->get('province');
            $district = $request->get('district');
            $municipality = $request->get('municipality');
            $ward = $request->get('ward');

            $filterParameters = [
                'province' => $province,
                'district' => $district,
                'municipality' => $municipality,
                'ward' => $ward,
            ];


            $stores_not_in_wards='';

            if(count(array_filter($filterParameters)) == 0){
                return sendSuccessResponse('No Stores Found !',[]);
            }

            $with=[ 'location'];
            $locationPath=StoreLocationFinder::getLocationPathInStoreFinder($filterParameters);
            $stores = StoreLocationFinder::findStoreLocation($filterParameters,10,$with);

            if(isset($stores) && $stores->count()){
                $result = ['stores'=>StoreFinderApiResource::collection($stores)];
                if(isset($filterParameters['municipality'])
                   && ($filterParameters['ward']==null)){
                    $stores_not_in_wards=StoreLocationFinder::storesNotInWards($stores);
                    $result['wards_with_no_stores'] = $stores_not_in_wards;
                }
                return response()->json($result);
            }
            else{
                return sendSuccessResponse('No stores have been registered in '.$locationPath,[]);
            }
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }
    public function findStoresInWard(Request $request)
    {
        try{

            $province = $request->get('province');
            $district = $request->get('district');
            $municipality = $request->get('municipality');
            $ward = $request->get('ward');
            if(empty($ward))
            {
                return sendSuccessResponse('You need to enter ward !',[]);
            }

            $filterParameters = [
                'province' => $province,
                'district' => $district,
                'municipality' => $municipality,
                'ward' => $ward,
            ];


            $with=[ 'location'];
            $locationPath=StoreLocationFinder::getLocationPathInStoreFinder($filterParameters);
            $stores = StoreLocationFinder::findStoreLocation($filterParameters,10,$with);

            if(isset($stores) && $stores->count()){
                $result = ['stores'=>StoreFinderApiResource::collection($stores)];
                return sendSuccessResponse('Stores found in '.$locationPath,$result);
            }
            else{
                return sendSuccessResponse('तपाइँले खोज्नु भएको वडा ( '.$locationPath.' ) खाली छ | यदि तपाइँ अलपसल संग सहकार्य गर्न चाहनु हुन्छ भने ९८६६६२२१००/९८६६६२२१०३/९८६६६२२१०५ मा सम्पर्क गर्नु होस्| ',[]);
            }
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getAllStoreLocations(){
        $stores = StoreLocationFinder::getAllStoreLocations();
        return sendSuccessResponse('Store - Locations Found !',$stores);
    }
}
