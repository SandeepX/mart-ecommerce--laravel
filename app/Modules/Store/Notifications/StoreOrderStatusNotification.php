<?php

namespace App\Modules\Store\Notifications;

use App\Modules\Store\Models\StoreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreOrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $storeOrder;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(StoreOrder $storeOrder)
    {
        $this->storeOrder = $storeOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Status Changed For Order : '. $this->storeOrder->store_order_code)
                    ->action('See Order Details ', config('site_urls.ecommerce_site').'/store-order/'.$this->storeOrder->store_order_code)
                    ->line($this->storeOrder->store_order_code.' Order Has Been '. $this->storeOrder->delivery_status);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Status for order : '.$this->storeOrder->store_order_code.'  has been '. $this->storeOrder->delivery_status,
            'url' => config('site_urls.ecommerce_site').'/store-order/'.$this->storeOrder->store_order_code,
            'image' => null
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
