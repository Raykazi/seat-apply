<?php

namespace Raykazi\Seat\SeatApplication\Notifications;

use Raykazi\Seat\SeatApplication\Notifications\Channels\DiscordChannel;
use Raykazi\Seat\SeatApplication\Notifications\Webhooks\Discord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationUpdated extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        $status = "";
        switch ($notifiable->status)
        {
            case 0:
                $status = "pending";
                break;
            case 1:
                $status = "reviewing";
                break;
            case 2:
                $status = "ready for interview";
                break;
            case 3:
                $status = "accepted";
                break;
            case -1:
                $status = "GTFO nerd";
                break;
        }
        return (new Discord)->post("```Application Update:```" .
            "\t\n$notifiable->approver has updated $notifiable->character_name's app to $status."
        );
    }


}
