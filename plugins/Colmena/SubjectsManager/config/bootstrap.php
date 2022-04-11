<?php

use Cake\Core\Configure;

/*
 * Generate menu items for the application menu
 */

Configure::write('Colmena/SubjectsManager.menuItems', [
    'Asignaturas' => [
        'order' => 2,
        'items' => [
            'Asignaturas' => [
                'link' => [
                    'controller' => 'Subjects',
                    'action' => 'index',
                    'plugin' => 'Colmena/SubjectsManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                ],
            ]
        ],
        'extra' => [
            'ico' => '<i class="fas fa-books"></i>',
        ],
    ],
]);

/*
 * Generate home page blocks
 */
Configure::write('Colmena/SubjectsManager.home_blocks', []);

/*
 * Set the parteable entities for this plugin
 */
// Configure::write('Colmena/SubjectsManager.parteable_entities', []);

/*
 * Set the searchable entities for this plugin
 */
Configure::write(
    'Colmena/SubjectsManager.searchable_entities',
    []
);

/*
 * Generate rolable entities array
 */
Configure::write('Colmena/SubjectsManager.rolable_entities', []);

/*
 * Special roles methods
 */
Configure::write('Colmena/SubjectsManager.special_method_entity_roles', []);

Configure::write('Colmena/SubjectsManager.api_entities', [
    'Colmena/SubjectsManager.Users' => [
        'order' => 1,
    ]
]);

/*
 * Configure Products Manager API routing
 */
Configure::write('Colmena/SubjectsManager.routes', [
    '/:version/users/:controller/:action' => [
        'defaults' => [
            'plugin' => 'Colmena/SubjectsManager',
        ],
        'options' => [],
    ],
]);
