<?php

namespace Raykazi\Seat\SeatApplication\Notifications;

use Illuminate\Notifications\Messages\SlackAttachmentField;
use Illuminate\Notifications\Messages\SlackMessage;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;
use Illuminate\Support\Facades\Log;

class ApplicationUpdated extends AbstractNotification
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
        switch ($this->application->status)
        {
            case -1:
                $this->status = "Rejected";
                $message->error();
                break;
            case 0:
                $this->status = "Pending";
                $message->warning();
                break;
            case 1:
                $this->status = "Reviewing";
                $message->warning();
                break;
            case 2:
                $this->status = "Ready For Interview";
                $message->success();
                break;
            case 3:
                $this->status = "Accepted";
                $message->success();
                break;
        }
        $message->content("$this->role");
        $message->from('Zahzi The Secretary');
        $message->attachment(function ($attachment) {
            $attachment
                ->title($this->application->approver." updated the status of an application")
                ->fields([
                    'Applicant' => $this->application->character_name,
                    'Status'    => $this->status,
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
