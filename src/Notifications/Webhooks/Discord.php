<?php

namespace Raykazi\Seat\SeatApplication\Notifications\Webhooks;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client as Requests;

class Discord
{
    public function post($content)
    {
        $url = env('APPLICATION_DISCORD_WEBHOOK_URL');
        if(!$url){
            return [500, 'APPLICATION_DISCORD_WEBHOOK_URL is not defined in .env'];
        }
        $srp_role_mention = env('APPLICATION_DISCORD_MENTION_ROLE');
        $headers = [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'content' => ($srp_role_mention ? $srp_role_mention . $content : $content)
            ],
        ];
        $request = new Requests();
        $response = $request->post($url, $headers);
        return [$response->getStatusCode(), $response];
    }
}
