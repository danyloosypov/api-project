<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferVehicleChangedNotification extends Notification
{
    use Queueable;

    public Transfer $transfer;
    public Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transfer $transfer, Order $order)
    {
        $this->transfer = $transfer;
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $this->transfer->vehicle;

        return (new MailMessage)
            ->subject('Your Transfer Vehicle Has Changed')
            ->greeting("Hello!")
            ->line("Your transfer for flight {$this->transfer->flight_num} has been updated.")
            ->line("New vehicle: {$vehicle->model} (License: {$vehicle->license})")
            ->action('View Your Order', url("/orders/{$this->order->id}"))
            ->line('Thank you for choosing us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transfer_id' => $this->transfer->id,
            'vehicle_model' => $this->transfer->vehicle->model,
            'vehicle_license' => $this->transfer->vehicle->license,
        ];
    }
}
