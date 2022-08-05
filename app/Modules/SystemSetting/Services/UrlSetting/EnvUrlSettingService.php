<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 10:52 AM
 */

namespace App\Modules\SystemSetting\Services\UrlSetting;


use App\Modules\SystemSetting\Models\EnvUrlSetting;
use App\Modules\SystemSetting\Repositories\UrlSetting\EnvUrlSettingRepository;

use Exception;
use DB;
use Illuminate\Support\Facades\Artisan;

class EnvUrlSettingService
{

    private $urlSettingRepository;

    public function __construct(EnvUrlSettingRepository $envUrlSettingRepository)
    {
        $this->urlSettingRepository = $envUrlSettingRepository;
    }

    public function getSiteUrlSetting(){
        return $this->urlSettingRepository->getFirst();
    }


    public function updateSiteUrlSetting($validatedData){
        try{
            $urlSetting =$this->urlSettingRepository->getFirst();
            // dd($passportSetting);
            DB::beginTransaction();
            if ($urlSetting){
                $urlSetting = $this->urlSettingRepository->update($urlSetting,$validatedData);
            }
            else{
                $urlSetting = $this->urlSettingRepository->save($validatedData);
            }
            $this->changeSiteUrlEnvironmentVariable($urlSetting);

            DB::commit();
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            return $urlSetting;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function changeSiteUrlEnvironmentVariable(EnvUrlSetting $envUrlSetting)
    {

        $envFile = app()->environmentFilePath();
        //$envFile = base_path('.env');

        $envUrlKeys = EnvUrlSetting::ENV_URL_KEYS;

        $dbUrlValues =[
            'ECOMMERCE_SITE_URL'=>$envUrlSetting->ecommerce_site_url,
        ];


        if (file_exists($envFile)) {

            foreach ($envUrlKeys as $urlKey=>$value){
                file_put_contents($envFile, str_replace(
                    $urlKey.'=' . config($value),
                    $urlKey.'=' .$dbUrlValues[$urlKey], file_get_contents($envFile)
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