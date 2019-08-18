<?php

return [
    'prefix'     => json_decode(file_get_contents(resource_path('config/env.json')), true)['local']['prefix'],
    'middleware' => [
        'api',
        'auth',
    ],
];
