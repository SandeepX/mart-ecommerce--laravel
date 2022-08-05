<?php

namespace App\Modules\Store\Notifications;

use App\Modules\Store\Models\StoreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreOrderNotification extends Notification implements ShouldQueue
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
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->storeOrder->store_order_code.' New Order Has Been Placed',
            'url' => route('admin.store.orders.show', $this->storeOrder->store_order_code),
            'image' => null,
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
