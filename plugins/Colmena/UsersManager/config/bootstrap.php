<?php

use Cake\Core\Configure;

/*
 * Generate menu items for the application menu
 */

Configure::write('Colmena/UsersManager.menuItems', [
    'Usuarios' => [
        'order' => 2,
        'items' => [
            'Alumnos' => [
                'icon' => '<i class="far fa-user"></i>',
                'link' => [
                    'controller' => 'Users',
                    'action' => 'index',
                    'plugin' => 'Colmena/UsersManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false
                ],
            ],
            'Grupos de prácticas' => [
                'icon' => '<i class="fad fa-users"></i>',
                'link' => [
                    'controller' => 'PracticeGroups',
                    'action' => 'index',
                    'plugin' => 'Colmena/UsersManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false
                ],
            ]
        ],
        'extra' => [
            'ico' => '<i class=" fa fa-solid fa-user"></i>',
        ],
    ],
]);

/*
 * Generate home page blocks
 */
Configure::write('Colmena/UsersManager.home_blocks', []);

/*
 * Set the parteable entities for this plugin
 */
// Configure::write('Colmena/UsersManager.parteable_entities', []);

/*
 * Set the searchable entities for this plugin
 */
Configure::write(
    'Colmena/UsersManager.searchable_entities',
    []
);

/*
 * Generate rolable entities array
 */
Configure::write('Colmena/UsersManager.rolable_entities', []);

/*
 * Special roles methods
 */
Configure::write('Colmena/UsersManager.special_method_entity_roles', []);

Configure::write('Colmena/UsersManager.api_entities', [
    'Colmena/UsersManager.Users' => [
        'order' => 1,
    ],
    'Colmena/UsersManager.PracticeGroups' => [
        'order' => 2,
    ]
]);

/*
 * Configure Products Manager API routing
 */
Configure::write('Colmena/UsersManager.routes', [
    '/:version/users/:controller/:action' => [
        'defaults' => [
            'plugin' => 'Colmena/UsersManager',
        ],
        'options' => [],
    ],
]);
