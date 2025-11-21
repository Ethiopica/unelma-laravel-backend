<?php

namespace App\Observers;

use App\Models\ContactMessage;

class ContactMessageObserver
{
    /**
     * Handle the ContactMessage "retrieved" event.
     * Note: This is called every time a model is retrieved from the database.
     * We'll only mark as read when explicitly viewing in admin panel, not on retrieval.
     */
    public function retrieved(ContactMessage $contactMessage): void
    {
        // Removed auto-marking as read on retrieval to prevent unintended side effects
        // Messages should only be marked as read when explicitly viewed in admin panel
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
