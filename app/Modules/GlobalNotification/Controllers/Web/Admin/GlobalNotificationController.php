<?php

namespace App\Modules\GlobalNotification\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\GlobalNotification\Helper\NotificationFilterHelper;
use App\Modules\GlobalNotification\Requests\NotificationStoreRequest;
use App\Modules\GlobalNotification\Requests\NotificationUpdate;
use App\Modules\GlobalNotification\Requests\NotificationUpdateRequest;
use App\Modules\GlobalNotification\Services\NotificationService;
use App\Modules\GlobalNotification\Requests\NotificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;


class GlobalNotificationController extends BaseController
{
    public $title = 'Notification';
    public $base_route = 'admin.notification';
    public $sub_icon = 'file';
    public $module = 'GlobalNotification::';
    public $view = 'global-notification.';

    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('permission:View Global Notification List', ['only' => ['index']]);
        $this->middleware('permission:Create Global Notification', ['only' => ['create','store']]);
        $this->middleware('permission:Show Global Notification', ['only' => ['show']]);
        $this->middleware('permission:Update Global Notification', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Global Notification', ['only' => ['destroy']]);
        $this->middleware('permission:Update Global Notification Status',['only'=>['toggleStatus']]);

        $this->notificationService = $notificationService;

    }

    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'created_for' =>$request->get('created_for'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'is_active'=> $request->get('is_active'),
            ];

            $allNotification = NotificationFilterHelper::getAllNotificationByFilter($filterParameters);

            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('allNotification','filterParameters'));
        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(NotificationStoreRequest $request)
    {
        $validated = $request->validated();
        //dd($validated);
        try{
            $notification =  $this->notificationService->storeNotification($validated);
            return redirect()->back()->with('success', $this->title . ':  Created Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function show($notificationCode)
    {

        $notificationDetail =  $this->notificationService->showDetailByCode($notificationCode);

        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('notificationDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($notificationCode)
    {

        $notificationDetail =  $this->notificationService->showDetailByCode($notificationCode);

        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('notificationDetail'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(NotificationUpdateRequest $request,$notificationCode)
    {
        $validated = $request->validated();
        //dd($request->all());
        try{
            $notification =  $this->notificationService->updateNotification($validated,$notificationCode);
            return redirect()->back()->with('success', $this->title . ':  updated Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($notificationCode)
    {
        try{
            $notificationDetail =  $this->notificationService->deleteNotification($notificationCode);

            return redirect()->back()->with('success', ' Notification Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function toggleStatus($notificationCode)
    {
        try{
            $this->notificationService->toggleNotification($notificationCode);
            return redirect()->back()->with('success', ' Notification status changed  Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}

