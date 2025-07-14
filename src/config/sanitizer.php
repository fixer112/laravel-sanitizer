<?php

return [

    // Automatically register the middleware globally
    'global' => false,

    // User-Agent substrings to allow (e.g., Laravel packages, tools)
    'allowed_agents' => [
        'laravel-http-client',
        'telescope',
        'symfony',
        'phpunit',
        'insomnia',
        'postmanruntime',
    ],

    // Substrings to detect as bots
    'blocked_agents' => [
        'bot',
        'crawler',
        'spider',
        'curl',
        'httpclient',
        'scrapy',
        'wget',
    ],

    'internal_paths' => [
        'vendor/*',
        'setting/*',
    ],

    // Optionally allow localhost requests (127.0.0.1 or ::1)
    'allow_localhost' => true,
];
