<?php

namespace Raykazi\Seat\SeatApplication\Notifications;

use Illuminate\Notifications\Messages\SlackAttachmentField;
use Illuminate\Notifications\Messages\SlackMessage;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;
use Illuminate\Support\Facades\Log;

class ApplicationDeleted extends AbstractNotification
{
    use NotificationTools;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $application;
    private $thumb;
    private $role;
    private $status;
    public function __construct(ApplicationModel $application)
    {
        $this->application = $application;
        $this->thumb = (new \Seat\Services\Image\Eve('characters', 'portrait', $this->application->user->main_character->character_id, 64, [], true))->url(64);
        $this->role = env('APPLICATION_DISCORD_MENTION_ROLE');
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
        $message = new SlackMessage;
        $message->error();
        $message->content("$this->role");
        $message->from('Zahzi The Secretary');
        $message->attachment(function ($attachment) {
            $attachment
                ->title($this->application->approver." deleted an application.")
                ->fields([
                    'Applicant' => $this->application->character_name
                ])
                ->fallback('App details')
                ->timestamp(carbon($this->application->updated_at))
                ->footer('SeAT Apply')
                ->thumb("https:$this->thumb")
                ->footerIcon('https://i.imgur.com/RJVGc7y.png');
            return $attachment;
        });
        return $message;
    }
}
