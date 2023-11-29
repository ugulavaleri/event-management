<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Notifications to all attendees which events are near than 24 hour!';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::with('attendees.user')
            ->whereBetween('start_time',[now(), now()->addDay()])
            ->get();

        $eventSum = $events->count();

        $attendees = $events->each(
            fn($event) => $event->attendees->each(
                fn($attendee) => $attendee->user->notify(
                    new EventReminderNotification($event)
                )
            )
        );

        $this->info("Send reminder! $eventSum");
    }
}
