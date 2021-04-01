<?php

namespace Raykazi\Seat\SeatApplication\Notifications;


use Illuminate\Notifications\Messages\SlackAttachmentField;
use Illuminate\Notifications\Messages\SlackMessage;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;
use Illuminate\Support\Facades\Log;

class ApplicationSubmitted extends AbstractNotification
{
    use NotificationTools;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $application;
    private $responses;
    private $appMessage;
    private $fields;
    private $thumb;
    private $role;
    public function __construct(ApplicationModel $application)
    {
        $this->application = $application;
        $this->thumb = (new \Seat\Services\Image\Eve('characters', 'portrait', $this->application->user->main_character->character_id, 64, [], true))->url(64);
        $this->role = env('APPLICATION_DISCORD_MENTION_ROLE');
        $this->responses = json_decode($this->application->responses,true);
        $keys = array_keys( $this->responses );
        $size = sizeof($this->responses);
        $this->fields = array();
        $this->fields[] = (new SlackAttachmentField)->title("SP")->content(number_format($this->application->user->main_character->skillpoints->total_sp));
        for($x = 0; $x < $size; $x++ ) {
            $attachmentField = new SlackAttachmentField;
            if($keys[$x] == "Alt Characters")
                $attachmentField->title($keys[$x])->content($this->responses[$keys[$x]]);
            else
                $attachmentField->title($keys[$x])->content($this->responses[$keys[$x]])->long();
            $this->fields[] = $attachmentField;
        }
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
            ->success()
            ->from('Zahzi The Secretary')
            ->content("$this->role")
            ->attachment(function ($attachment) {
                $attachment
                    ->fields($this->fields)
                    ->title($this->application->character_name." wants to join your corporation")
                    ->fallback('App details')
                    ->footer('SeAT Apply')
                    ->thumb("https:$this->thumb")
                    ->timestamp(carbon($this->application->created_at))
                    ->footerIcon('https://i.imgur.com/RJVGc7y.png');
                return $attachment;
            });

        return $message;
    }


}
