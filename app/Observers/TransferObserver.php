<?php

namespace App\Observers;

use App\Models\MySql\Transfer;
use App\Notifications\TransferStatusChangedNotification;

class TransferObserver
{
    /**
     * Handle the Transfer "created" event.
     */
    public function created(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "updated" event.
     */
    public function updated(Transfer $transfer): void
    {
        if ($transfer->isDirty('status')) {
            foreach ($transfer->orders as $order) {
                $order->notify(new TransferStatusChangedNotification($transfer, $order));
            }
        }

        if ($transfer->isDirty('vehicle_id')) {
            foreach ($transfer->orders as $order) {
                $order->notify(new TransferStatusChangedNotification($transfer, $order));
            }
        }
    }

    /**
     * Handle the Transfer "deleted" event.
     */
    public function deleted(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "restored" event.
     */
    public function restored(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "force deleted" event.
     */
    public function forceDeleted(Transfer $transfer): void
    {
        //
    }
}
