<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * @see          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 *
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';
include_once CORE_PATH . 'config' . DS . 'config.php';

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Utility\Inflector;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;

/*
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.default to `config/.env` and set/modify the
 * variables as required.
 *
 * It is HIGHLY discouraged to use a .env file in production, due to security risks
 * and decreased performance on each request. The purpose of the .env file is to emulate
 * the presence of the environment variables like they would be present in production.
 *
 */
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */

try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file.
 * You can use a file like app_local.php to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local', 'default');

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
    // disable router cache during development
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Email::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
//Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/*
 * Setup detectors for mobile and tablet.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
Type::build('time')
    ->useImmutable();
Type::build('date')
    ->useImmutable();
Type::build('datetime')
    ->useImmutable();
Type::build('timestamp')
    ->useImmutable();

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);
//Inflector::rules('transliteration', ['/å/' => 'aa']);

/*
 * Load languages config
 */
Configure::load('i18n', 'default');
Cache::disable();
/*
 * Set default time & date format config for strings to show to the user
 */
Configure::write('I18N.formats.default', Configure::read('I18N.formats.' . Configure::read('I18N.language')));

/*
 * Date & Time config
 * Set the default format for the Date and Time objects that are converted to strings
 */
Time::setToStringFormat(
    Configure::read('I18N.formats.default.date') .
        ' ' .
        Configure::read('I18N.formats.default.time')
);
Date::setToStringFormat(Configure::read('I18N.formats.default.date'));
FrozenTime::setToStringFormat(
    Configure::read('I18N.formats.default.date') .
        ' ' .
        Configure::read('I18N.formats.default.time')
);
FrozenDate::setToStringFormat(Configure::read('I18N.formats.default.date'));

/*
 * Project.version: The current CMS version. Update this number in every release.
 * Don't update this in website CMS versions.
 */
Configure::write('Project.version', '0.24.9');

Configure::write(
    'Config.base_url',
    Configure::read('Config.protocol') . '://' . Configure::read('Config.domain') . '/' . Configure::read('Config.admin_path')
);
Configure::write(
    'Config.web_url',
    Configure::read('Config.protocol') . '://' . Configure::read('Config.domain') . '/' . Configure::read('Config.web_path')
);

/*
 * If needed, change this value according to the URL of the location of the resources
 */
Configure::write(
    'Config.resources_url',
    Configure::read('Config.protocol') . '://' . Configure::read('Config.domain') . '/' . Configure::read('Config.web_path') . '/'
);

/*
* Active vendor plugins in colmena-admin
*/
Configure::write('vendor_plugins', [
    'Mailgun',
    'CakePdf',
]);

/*
* Active plugins in colmena-admin
*/
$pluginArr = [];
$directory = '../plugins/Colmena';
$files = scandir($directory);
array_splice($files, 0, 2);

foreach ($files as $plugin) {
    $pluginPath = 'Colmena/' . $plugin;
    array_push($pluginArr, $pluginPath);
}

Configure::write('plugins', $pluginArr);

/*
 * SESSION CONFIG
 */
Configure::write('Session', [
    'defaults' => 'php',
    'timeout' => 240, //minutes
    'cookie' => 'CAKEPHP-' . Configure::read('Cookie.name'),
    'cookiePath' => '/' . Configure::read('Config.admin_path'),
    'ini' => [
        'session.cookie_lifetime' => 14400, //seconds (240 minutes)
        'session.cookie_domain' => Configure::read('Config.domain'),
    ],
]);

/*
 * Admin menu items
 */
Configure::write('Admin.menuItems', [
    'Administración' => [
        'order' => 13,
        'items' => [
            'Ver usuarios de administración' => [
                'icon' => '<i class="far fa-tools"></i>',
                'link' => [
                    'controller' => 'AdminUsers',
                    'action' => 'index',
                    'plugin' => false,
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false
                ],
            ]
        ],
        'extra' => [
            'ico' => '<i class="fas fa-cog fa-lg fa-fw"></i>',
        ],
    ],
]);

/*
 * Set the entities affected by user roles with array_merge
 */
Configure::write('Admin.rolable_entities', [
    'Contacts' => 'Direcciones',
    'AdminUsers' => 'Usuarios de administración',
    'AdminUserRoles' => 'Roles de usuario',
]);

/*
 * Special method entity roles
 */
Configure::write('Admin.special_method_entity_roles', [
    'index,add,edit' => [
        'Users' => [
            'userRoles',
        ],
    ],
]);

/*
 * Configure the entities that can be queried using the API
 */
Configure::write('Admin.api_entities', [
    'Url' => [
        'order' => 1,
    ],
]);

$default_classes = [
    'test1' => [
        'name' => 'Test1',
        'img' => 'webroot/img/advanced-configuration/test1.png',
        // 'order' => 0,
    ],
    'test2' => [
        'name' => 'Test2',
        'img' => 'webroot/img/advanced-configuration/test2.png',
        // 'order' => 0,
    ]
];

$default_classes['wrapped']['from'] = [];
$default_classes['wrapped']['to'] = [];
for ($i = 1; $i <= 13; $i++) {
    if ($i < 10) {
        $num = str_pad($i, 2, 0, STR_PAD_LEFT);
    } else {
        $num = $i;
    }

    if ($i >= 1 && $i <= 12) {
        $class = [
            'wrapped-from-' . $i => [
                'img' => 'webroot/img/advanced-configuration/wrapped-' . $i . '.png',
            ]
        ];
        $default_classes['wrapped']['from'] += $class;
    }
    if ($i >= 2 && $i <= 13) {
        $class = [
            'wrapped-to-' . $i => [
                'img' => 'webroot/img/advanced-configuration/wrapped-' . $i . '.png',
            ]
        ];

        $default_classes['wrapped']['to'] += $class;
    }
}

$default_classes_formatted = [];
foreach ($default_classes as $class => $value) {
    $name = isset($value['name']) ? $value['name'] : $class;
    $default_classes_formatted[$class] = $name;

    if (isset($value['img'])) {
        $default_classes[$class]['img'] = Configure::read('Config.base_url') . $value['img'];
    }
}

/*
 * Configure the generic parameters
 */
Configure::write('Admin.parameters', [
    'entities' => 'all',
    'default_classes' => $default_classes,
    'config' => [
        'classes' => [
            'type' => 'form-control',
            'config' => [
                'label' => 'Clases',
                'type' => 'select',
                'class' => 'keywords',
                'multiple' => true,
                'options' => $default_classes_formatted
            ]
        ],
        // 'variables' => [
        //     'type' => 'variables',
        //     'config' => [
        //         'name' => 'Variables',
        //     ]
        // ],
        'wrapped' => [
            'type' => 'wrapped',
            'config' => [
                'name' => 'Clases Wrapped',
            ]
        ],
        'decoration-images' => [
            'type' => 'decoration-images',
            'config' => [
                'name' => 'Imágenes decorativas',
            ]
        ]
    ],
]);

/*
 * Set API key of TinyPNG to reduce jpg and png images 
 */
Configure::write("tinypng.api_key", "XHWMjj1DlYRCz0lzBW7R4fWQPvg0W9FV");
Configure::write("tinypng.available_extensions", [
    'jpg',
    'jpeg',
    'png'
]);

/*
 * Configure the entities that can be queried using the API
 */
Configure::write('Config.tab_actions', [
    'Caché' => [
        'url' => [
            'controller' => 'Caches',
            'action' => 'index'
        ],
        'tab' => 'social',
        'current' => '',
        'i18n' => false
    ],
    'Notificaciones' => [
        'url' => [
            'controller' => 'Notifications',
            'action' => 'index'
        ],
        'tab' => 'social',
        'current' => '',
        'i18n' => false
    ]
]);

// PRO CDN
Configure::write('fa_cdn', [
    'href' => 'https://pro.fontawesome.com/releases/v5.12.0/css/all.css',
    'integrity' => 'sha384-ekOryaXPbeCpWQNxMwSWVvQ0+1VrStoPJq54shlYhR8HzQgig1v5fas6YgOqLoKz',
]);

/*
 * Load email configuration
 */
Configure::load('email', 'default');
TransportFactory::setConfig(Configure::read('Config.email_transport'));
Email::setConfig(Configure::read('Config.email'));

/*
 * Load default colors
 */

Configure::load('colors', 'default');
/*
 * Load default SEO config
 */
Configure::load('seo', 'default');

/*
* Load configuration for Vue.js
*/
header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: *');