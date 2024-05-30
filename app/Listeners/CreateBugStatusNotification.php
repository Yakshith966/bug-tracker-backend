<?php

namespace App\Listeners;

use App\Events\BugStatusChanged;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateBugStatusNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BugStatusChanged $event)
    {
        // Extract the bug from the event
        $bug = $event->bug;

        // Create a new notification
        Notification::create([
            'title' => $bug->name, // Use the name or title of the bug
            'status' => $bug->status, // Use the status of the bug
            'date' => now(), // Use the current date and time
        ]);
    }
}
