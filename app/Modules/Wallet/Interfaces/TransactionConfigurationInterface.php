<?php
namespace App\Modules\Wallet\Interfaces;

interface TransactionConfigurationInterface
{
    public function setSMSSendStatus($status);
    public function setMailSendStatus($status);
    public function setWEBNotificationSendStatus($status);

}
