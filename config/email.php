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
                'abel.velarde@neozink.com' => 'Neosite Admin',
            ],
            'contact.to' => [
                'desarrollo@neozink.com' => 'Neosite Admin',
            ],
            'contact.from' => [
                'miguelputoamo@neozink.com' => 'Tierra Astur ',
            ],
            'notifications.from' => [
                'abel.velarde@neozink.com' => 'Neosite Notifications',
            ],
            'notifications.replyto' => [
                'abel.velarde@neozink.com' => 'Neosite Admin',
            ],
            'notifications.admin' => [
                'elmer.cortez@neozink.com' => 'Neosite Admin',
            ],
            'shop.from' => [
                'miguel.riesco@neozink.com' => 'Shop Admin',
            ],
        ],
    ],
];
