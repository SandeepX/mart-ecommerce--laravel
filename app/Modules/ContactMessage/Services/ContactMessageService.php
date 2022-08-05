<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/20/2020
 * Time: 5:21 PM
 */

namespace App\Modules\ContactMessage\Services;


use App\Modules\ContactMessage\Repositories\ContactMessageRepository;

use Exception;
use DB;

class ContactMessageService
{

    private $contactMessageRepository;

    public function __construct(ContactMessageRepository $contactMessageRepository){

        $this->contactMessageRepository= $contactMessageRepository;
    }

    public function getAllContactMessage(){
        return $this->contactMessageRepository->getAll();
    }

    public static function findOrFailContactMessageById($id){

        return ContactMessageRepository::findOrFailById($id);
    }

    public function saveContactMessage($validatedData){

        try{
            DB::beginTransaction();
            $contactMessage= $this->contactMessageRepository->save($validatedData);
            DB::commit();
            return $contactMessage;
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

    }
}