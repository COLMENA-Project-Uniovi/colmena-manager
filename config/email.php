<?php

/**
 * Email configuration.
 */
return [
    'Config' => [
        /*
         * Configure the email transport configuration
         */
        'email_transport' => [
            'mailgun' => [
                'className' => 'Mailgun.Mailgun',
                'apiEndpoint' => 'https://api.mailgun.net/v3',
                'domain' => 'mg.neozink.com',
                'apiKey' => 'key-24daf63f4717b01caea6dbe7db6d576d',
            ],
        ],
        /*
         * Configure the email profile with the transport associated
         */
        'email' => [
            'mailgun' => [
                'transport' => 'mailgun',
            ],
        ],
        /*
         * Configure the profile to use by default in the emails of the application
         */
        'email_properties' => [
            'profile' => 'mailgun',
            'test_email' => [
                'borjarodrilore@gmail.com' => 'Colmena Admin',
            ],
            'contact.to' => [
                'borjarodrilore@gmail.com' => 'Colmena Admin',
            ],
            'contact.from' => [
                'borjarodrilore@gmail.com' => 'Colmena',
            ],
            'notifications.from' => [
                'borjarodrilore@gmail.com' => 'Colmena Notifications',
            ],
            'notifications.replyto' => [
                'borjarodrilore@gmail.com' => 'Colmena Admin',
            ],
            'notifications.admin' => [
                'borjarodrilore@gmail.com' => 'Colmena Admin',
            ],
            'shop.from' => [
                'borjarodrilore@gmail.com' => 'Shop Admin',
            ],
        ],
    ],
];
