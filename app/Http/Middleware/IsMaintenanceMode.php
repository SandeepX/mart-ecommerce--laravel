<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\MaintenanceModeOnException;
use App\Modules\SystemSetting\Services\GeneralSetting\GeneralSettingService;
use Closure;

class IsMaintenanceMode
{

    protected $generalSettingService;

    public function __construct(GeneralSettingService $GeneralSettingService)
    {
        $this->generalSettingService = $GeneralSettingService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $maintenanceModeEnabled =  $this->generalSettingService->isMaintenanceModeOn();

        if(!$maintenanceModeEnabled){
            return $next($request);
        }
        
        throw new MaintenanceModeOnException('We are in maintenance mode .');
    }
}
