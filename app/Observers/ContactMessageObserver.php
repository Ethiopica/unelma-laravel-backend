<?php

namespace App\Observers;

use App\Models\ContactMessage;

class ContactMessageObserver
{
    /**
     * Handle the ContactMessage "created" event.
     */
    public function retrieved(ContactMessage $contactMessage): void
    {
        $contactMessage->is_read = true;
        $contactMessage->save();
    }
    public function created(ContactMessage $contactMessage): void
    {
        //
    }

    /**
     * Handle the ContactMessage "updated" event.
     */
    public function updated(ContactMessage $contactMessage): void
    {
        //
    }

    /**
     * Handle the ContactMessage "deleted" event.
     */
    public function deleted(ContactMessage $contactMessage): void
    {
        //
    }

    /**
     * Handle the ContactMessage "restored" event.
     */
    public function restored(ContactMessage $contactMessage): void
    {
        //
    }

    /**
     * Handle the ContactMessage "force deleted" event.
     */
    public function forceDeleted(ContactMessage $contactMessage): void
    {
        //
    }
}
