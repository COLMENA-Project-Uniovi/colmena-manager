<?php

use Cake\Core\Configure;

/*
 * Generate menu items for the application menu
 */

Configure::write('Colmena/ErrorsManager.menuItems', [
    'Errores' => [
        'order' => 1,
        'items' => [
            'Errores' => [
                'icon' => '<i class="far fa-bug"></i>',
                'link' => [
                    'controller' => 'Errors',
                    'action' => 'index',
                    'plugin' => 'Colmena/ErrorsManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false,
                ],
            ],
            'Markers' => [
                'icon' => '<i class="far fa-bookmark"></i>',
                'link' => [
                    'controller' => 'Markers',
                    'action' => 'index',
                    'plugin' => 'Colmena/ErrorsManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false
                ],
            ],
            'Compilaciones' => [
                'icon' => '<i class="far fa-bug"></i>',
                'link' => [
                    'controller' => 'Compilations',
                    'action' => 'index',
                    'plugin' => 'Colmena/ErrorsManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false,
                ],
            ],
        ],
        'extra' => [
            'ico' => '<i class=" fa fa-solid fa-user"></i>',
        ],
    ],
]);

/*
 * Generate home page blocks
 */
Configure::write('Colmena/ErrorsManager.home_blocks', []);

/*
 * Set the searchable entities for this plugin
 */
Configure::write(
    'Colmena/ErrorsManager.searchable_entities',[]
);

/*
 * Generate rolable entities array
 */
Configure::write('Colmena/ErrorsManager.rolable_entities', []);

/*
 * Special roles methods
 */
Configure::write('Colmena/ErrorsManager.special_method_entity_roles', []);

Configure::write('Colmena/ErrorsManager.api_entities', [
    'Colmena/ErrorsManager.Errors' => [
        'order' => 1,
    ],
    'Colmena/ErrorsManager.Markers' => [
        'order' => 2,
    ]
]);

/*
 * Configure Products Manager API routing
 */
Configure::write('Colmena/ErrorsManager.routes', [
    '/:version/errors/:controller/:action' => [
        'defaults' => [
            'plugin' => 'Colmena/ErrorsManager',
        ],
        'options' => [],
    ],
]);
