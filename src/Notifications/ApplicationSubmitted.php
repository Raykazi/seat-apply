<?php

namespace Raykazi\Seat\SeatApplication\Notifications;

use Raykazi\Seat\SeatApplication\Notifications\Channels\DiscordChannel;
use Raykazi\Seat\SeatApplication\Notifications\Webhooks\Discord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationSubmitted extends Notification
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
        return (new Discord)->post("```New Application Received:```" .
            "\t**Requested On:** $notifiable->created_at" .
            "\n\t**Applicant:** " . $notifiable::join('users as u', 'user_id', 'u.id')->select('u.name')->first()->name .
            "\n\t**Applicant:** $notifiable->character_name"
        );
    }


}
