<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Contact Form Email
    |--------------------------------------------------------------------------
    |
    | The email address where contact form submissions will be sent.
    | This can be a single email or comma-separated list of emails.
    |
    */
    'email' => env('CONTACT_FORM_EMAIL', 'admin@tarqumi.com'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Maximum number of contact form submissions per minute per IP address.
    |
    */
    'rate_limit' => 5,
    'rate_limit_minutes' => 1,
];
