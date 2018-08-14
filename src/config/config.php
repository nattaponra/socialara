<?php

return [
    'providers' => [
        'facebook' => [
            'client_id'     => env('FACEBOOK_CLIENT_ID',null),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET',null),
            'redirect'      => env('FACEBOOK_REDIRECT',null),
        ],
    ]


];