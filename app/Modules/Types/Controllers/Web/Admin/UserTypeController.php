<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Requests\UserType\UserTypeCreateRequest;
use App\Modules\Types\Requests\UserType\UserTypeUpdateRequest;
use App\Modules\Types\Services\UserTypeService;


class UserTypeController extends BaseController
{
    public $title = 'User Type';
    public $base_route = 'admin.user-types';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $userTypeService;


    public function __construct(UserTypeService $userTypeService)
    {
        $this->view = 'admin.user-types.';
        $this->userTypeService = $userTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userTypes = $this->userTypeService->getAllUserTypes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('userTypes'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserTypeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $userType =  $this->userTypeService->storeUserType($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $userType->user_type_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($userTypeCode)
    {
        try{
            $userType = $this->userTypeService->findOrFailUserTypeByCode($userTypeCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('userType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserTypeUpdateRequest $request,$userTypeCode)
    {
        $validated = $request->validated();
        try{
            $userType = $this->userTypeService->updateUserType($validated, $userTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $userType->user_type_name .' Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userTypeCode)
    {
        try{
            $userType = $this->userTypeService->deleteUserType($userTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $userType->user_type_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
