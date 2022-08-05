<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/20/2020
 * Time: 5:41 PM
 */

namespace App\Modules\ContactMessage\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\ContactMessage\Services\ContactMessageService;

use Exception;

class ContactMessageController extends BaseController
{

    public $title = 'Contact Message';
    public $base_route = 'admin.contact-messages.';
    public $sub_icon = 'file';
    public $module = 'ContactMessage::';

    private $view='admin.contact-message.';

    private $contactMessageService;

    public function __construct(ContactMessageService $contactMessageService){

        $this->middleware('permission:View Contact Message List', ['only' => ['index']]);
        $this->middleware('permission:Show Contact Message', ['only' => ['show']]);
        $this->contactMessageService = $contactMessageService;
    }

    public function index(){

        $contactMessages = $this->contactMessageService->getAllContactMessage();

        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('contactMessages'));
    }

    public function show($id){
        try{

            $contactMessage = $this->contactMessageService->findOrFailContactMessageById($id);

            return view(Parent::loadViewData($this->module.$this->view.'show'),compact('contactMessage'));

        }catch (Exception $ex){
            return redirect()->route($this->base_route.'index')->with('danger',$ex->getMessage());
        }
    }
}
