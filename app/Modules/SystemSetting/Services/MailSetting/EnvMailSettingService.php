<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/6/2020
 * Time: 2:11 PM
 */

namespace App\Modules\SystemSetting\Services\MailSetting;


use App\Modules\SystemSetting\Models\EnvMailSetting;
use App\Modules\SystemSetting\Repositories\MailSetting\EnvMailSettingRepository;
use Exception;
use DB;
use Illuminate\Support\Facades\Artisan;

class EnvMailSettingService
{

    private $mailSettingRepository;

    public function __construct(EnvMailSettingRepository $mailSettingRepository)
    {
        $this->mailSettingRepository = $mailSettingRepository;
    }

    public function getMailSetting(){
        return $this->mailSettingRepository->getFirst();
    }

    public function getMailDrivers(){

        return EnvMailSetting::MAIL_DRIVERS;
    }

    public function updateMailSetting($validatedData){
        try{
            $mailSetting =$this->mailSettingRepository->getFirst();
           // dd($mailSetting);
            DB::beginTransaction();
            if ($mailSetting){
                $mailSetting = $this->mailSettingRepository->update($mailSetting,$validatedData);
            }
            else{
                $mailSetting = $this->mailSettingRepository->save($validatedData);
            }
            $this->changeMailEnvironmentVariable($mailSetting);

            DB::commit();
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            return $mailSetting;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


    private function changeMailEnvironmentVariable(EnvMailSetting $envMailSetting)
    {

        $envFile = app()->environmentFilePath();
        //$envFile = base_path('.env');

        $envMailKeys = EnvMailSetting::ENV_MAIL_KEYS;

        $dbMailValues =[
            'MAIL_MAILER'=>$envMailSetting->mail_mailer,
            'MAIL_HOST'=>$envMailSetting->mail_host,
            'MAIL_PORT'=>$envMailSetting->mail_port,
            'MAIL_USERNAME'=>$envMailSetting->mail_username,
            'MAIL_PASSWORD'=>$envMailSetting->mail_password,
            'MAIL_ENCRYPTION'=>$envMailSetting->mail_encryption,
            'MAIL_FROM_ADDRESS'=>$envMailSetting->mail_from_address,
            'MAIL_FROM_NAME'=>$envMailSetting->mail_from_name,
        ];


        if (file_exists($envFile)) {

            foreach ($envMailKeys as $mailKey=>$value){
                file_put_contents($envFile, str_replace(
                    $mailKey.'=' . config($value),
                    $mailKey.'=' .$dbMailValues[$mailKey], file_get_contents($envFile)
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