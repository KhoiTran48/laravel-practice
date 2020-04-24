<?php

namespace App\Listeners;

use App\Events\TaskEvent;
use App\Mail\SendEmailMailable;
use App\Notifications\TaskCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class TaskEventListener
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
     * @param  TaskEvent  $event
     * @return void
     */
    public function handle(TaskEvent $event)
    {
        $event->admin->notify(new TaskCompleted());

        // Notification::route('mail', 'taylor@example.com')
        //     // ->route('nexmo', '5555555555')
        //     // ->route('slack', 'https://hooks.slack.com/services/...')
        //     ->notify(new TaskCompleted());

        // Mail::to("trandinhkhoi48@gmail.com")->send(new SendEmailMailable());
    }
}
