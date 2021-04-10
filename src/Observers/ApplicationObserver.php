<?php

namespace Raykazi\Seat\SeatApplication\Observers;

use Illuminate\Support\Facades\Notification;
use Seat\Notifications\Models\NotificationGroup;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Illuminate\Support\Facades\Log;

/**
 * Class UserNotificationObserver.
 *
 * @package Seat\Notifications\Observers
 */
class ApplicationObserver
{
    /**
     * @param \Seat\Web\Models\Squads\SquadApplication $member
     */
    public function created(ApplicationModel $member)
    {
        $this->dispatch($member,  0);
    }
    public function updated(ApplicationModel $member)
    {
        $this->dispatch($member, 1);
    }
    public function deleting(ApplicationModel $member)
    {
        $this->dispatch($member, 2);
    }

    /**
     * Queue notification based on User Creation.
     *
     * @param \Seat\Web\Models\Squads\SquadApplication $member
     */
    private function dispatch(ApplicationModel $member, $mode)
    {
        // detect handlers setup for the current notification
        switch ($mode)
        {
            case 0:
                $handlers = config('notifications.alerts.application_submitted.handlers', []);
                break;
            case 1:
                $handlers = config('notifications.alerts.application_status_changed.handlers', []);
                break;
            case 2:
                $handlers = config('notifications.alerts.application_deleted.handlers', []);
                break;

        }

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates();
        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // attempt to enqueue a notification for each routing candidates
        $routes->each(function ($integration) use ($handlers, $member) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                // enqueue the notification
                $res = Notification::route($integration->channel, $integration->route)->notify(new $handler($member));;

            }
        });

    }
    /**
     * Provide a unique list of notification channels (including driver and route).
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates()
    {
        $settings = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'application_submitted');
            })->get();

        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $settings = (array) $channel->settings;
                $key = array_key_first($settings);
                $route = $settings[$key];

                // build a composite object built with channel and route
                return (object) [
                    'channel' => $channel->type,
                    'route' => $route,
                ];
            });
        });

        return $routes->flatten()->unique(function ($integration) {
            return $integration->channel . $integration->route;
        });
    }
}
