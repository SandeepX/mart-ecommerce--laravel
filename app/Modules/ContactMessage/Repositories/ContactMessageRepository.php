<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/20/2020
 * Time: 5:22 PM
 */

namespace App\Modules\ContactMessage\Repositories;


use App\Modules\ContactMessage\Models\ContactMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContactMessageRepository
{
    public function getAll(){

        return ContactMessage::latest()->get();
    }

    public static function findOrFailById($id){

        $contactMessage = ContactMessage::where('id',$id)->first();

        if (!$contactMessage){
            throw new ModelNotFoundException('Contact message not found for the id');
        }

        return $contactMessage;
    }

    public function save($validatedData){
        return ContactMessage::create($validatedData)->fresh();
    }
}