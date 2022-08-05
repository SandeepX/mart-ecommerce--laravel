<?php

/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/7/2020
 * Time: 3:35 PM
 */
namespace App\Modules\SystemSetting\Services\PassportSetting;

use App\Modules\SystemSetting\Models\EnvPassportSetting;
use App\Modules\SystemSetting\Repositories\PassportSetting\EnvPassportSettingRepository;
use Exception;
use DB;
use Illuminate\Support\Facades\Artisan;

class EnvPassportSettingService
{

    private $passportSettingRepository;

    public function __construct(EnvPassportSettingRepository $envPassportSettingRepository)
    {
        $this->passportSettingRepository = $envPassportSettingRepository;
    }

    public function getPassportSetting(){
        return $this->passportSettingRepository->getFirst();
    }


    public function updatePassportSetting($validatedData){
        try{
            $passportSetting =$this->passportSettingRepository->getFirst();
            // dd($passportSetting);
            DB::beginTransaction();
            if ($passportSetting){
                $passportSetting = $this->passportSettingRepository->update($passportSetting,$validatedData);
            }
            else{
                $passportSetting = $this->passportSettingRepository->save($validatedData);
            }
            $this->changePassportEnvironmentVariable($passportSetting);

            DB::commit();
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            return $passportSetting;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function changePassportEnvironmentVariable(EnvPassportSetting $envPassportSetting)
    {

        $envFile = app()->environmentFilePath();
        //$envFile = base_path('.env');

        $envPassportKeys = EnvPassportSetting::ENV_PASSPORT_KEYS;

        $dbPassportValues =[
            'PASSPORT_LOGIN_ENDPOINT'=>$envPassportSetting->passport_login_endpoint,
            'PASSPORT_CLIENT_ID'=>$envPassportSetting->passport_client_id,
            'PASSPORT_CLIENT_SECRET'=>$envPassportSetting->passport_client_secret,
        ];


        if (file_exists($envFile)) {

            foreach ($envPassportKeys as $passportKey=>$value){
                file_put_contents($envFile, str_replace(
                    $passportKey.'=' . config($value),
                    $passportKey.'=' .$dbPassportValues[$passportKey], file_get_contents($envFile)
                ));
            }
            /* example for reference
            file_put_contents($path, str_replace(
                 'MAIL_MAILER=' . config('mail.default'),
                 'MAIL_MAILER=' . 'test', file_get_contents($path)
             ));
            */
        }
    }

}