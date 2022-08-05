<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 12:43 PM
 */

namespace App\Modules\Newsletter\Services;

use App\Modules\Newsletter\Jobs\SendMailJob;
use App\Modules\Newsletter\Mails\ConfirmSubscriptionMail;
use App\Modules\Newsletter\Models\Subscriber;
use App\Modules\Newsletter\Repositories\SubscriberRepository;
use Exception;
use Illuminate\Support\Str;

use DB;

class SubscriberService
{
    private $subscriberRepository;

    public function __construct(SubscriberRepository $subscriberRepository){

        $this->subscriberRepository = $subscriberRepository;
    }

    public function getAllSubscribers(){
        return $this->subscriberRepository->getAll();
    }

    public function storeSubscriber($validatedData){
        try{
            $validatedData['token']=Str::random(40);
            $validatedData['is_active']=1;
            DB::beginTransaction();
            $subscriber = $this->subscriberRepository->save($validatedData);
          //  $this->sendConfirmationMail($subscriber);
            DB::commit();
            return $subscriber;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function sendConfirmationMail(Subscriber $subscriber){

        $data = array(
            'email' => $subscriber->email,
            //'route_link'=>route('fe.confirmSubscription',$subscriber->token),
            'route_link' => "http://allpasal.allkhata.com/newsletter/subscribe/confirmation".$subscriber->token
        );

        SendMailJob::dispatch($subscriber->email,new ConfirmSubscriptionMail($data));

    }

    public function confirmSubscription($token){

        try{
            $subscriber = $this->subscriberRepository->findOrFailByToken($token);
            DB::beginTransaction();
            $data['token'] = null;
            $data['is_active'] =1;
            $this->subscriberRepository->update($subscriber,$data);

            DB::commit();

            return $subscriber;
        }catch (Exception $exception){
            DB::rollBack();
            throw  $exception;
        }

    }

    public function updateStatus($code){

        try{
            $subscriber = $this->subscriberRepository->findOrFailByCode($code);
            DB::beginTransaction();
            $subscriber->is_active == 1?$data['is_active'] =0 :$data['is_active']=1;
            $this->subscriberRepository->update($subscriber,$data);
            DB::commit();
            return $subscriber;
        }catch (Exception $exception){
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteSubscriber($code){

        try{
            $subscriber = $this->subscriberRepository->findOrFailByCode($code);
            DB::beginTransaction();
            $this->subscriberRepository->delete($subscriber);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw  $exception;
        }
    }
}