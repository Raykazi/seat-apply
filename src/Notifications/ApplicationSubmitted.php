<?php

namespace Raykazi\Seat\SeatApplication\Notifications;

use Raykazi\Seat\SeatApplication\Notifications\Channels\DiscordChannel;
use Raykazi\Seat\SeatApplication\Notifications\Webhooks\Discord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

class ApplicationSubmitted extends AbstractNotification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }
    public function toSlack($notifiable)
    {
        $message = (new SlackMessage)
            ->content('A nerd wants to join your corporation!')
            ->from('SeAT Applications', $this->typeIconUrl('https://zkillboard.com/img/wreck.png'));


        return $message;
    }


}
