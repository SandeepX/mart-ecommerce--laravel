<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 12:47 PM
 */

namespace App\Modules\Newsletter\Repositories;


use App\Modules\Newsletter\Models\Subscriber;

class SubscriberRepository
{

    public function getAll(){
        return Subscriber::latest()->get();
    }

    public function findOrFailByToken($token){
        return Subscriber::where('token',$token)->firstOrFail();
    }

    public function findOrFailByCode($subscriberCode){
        return Subscriber::where('subscriber_code',$subscriberCode)->firstOrFail();
    }


    public function save($validatedData){
        return Subscriber::create($validatedData)->fresh();
    }

    public function Update(Subscriber $subscriber,$data){
        $subscriber->update($data);
        return $subscriber->fresh();
    }

    public function delete(Subscriber $subscriber) {
        $subscriber->delete();
        return $subscriber;
    }
}