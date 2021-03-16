<?php

return [
    'application_submitted' => [
        'label' => 'Application Submitted',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\CreatedUser::class,
        ],
    ],
    'application_status_changed' => [
        'label' => 'Application Status Changed',
        'handlers' => [
            'slack' => \Seat\Notifications\Notifications\Seat\Slack\DisabledToken::class,
        ],
    ]
];
