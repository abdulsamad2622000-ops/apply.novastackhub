<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Password
    |--------------------------------------------------------------------------
    |
    | This password protects the /admin dashboard where submitted applications
    | can be reviewed. Set it via the ADMIN_PASSWORD value in your .env file.
    |
    */

    'admin_password' => env('ADMIN_PASSWORD', 'change-me'),

    /*
    |--------------------------------------------------------------------------
    | Notification Email
    |--------------------------------------------------------------------------
    |
    | When a new application is submitted, a short notification is emailed to
    | this address. Leave RECRUITMENT_NOTIFY_EMAIL blank in .env to disable.
    |
    */

    'notify_email' => env('RECRUITMENT_NOTIFY_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Role Being Hired
    |--------------------------------------------------------------------------
    */

    'role_title' => 'Client Acquisition Specialist',

];
