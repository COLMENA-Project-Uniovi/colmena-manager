<?php

use Cake\Core\Configure;

/*
 * Generate menu items for the application menu
 */

Configure::write('Colmena/AcademicalManager.menuItems', [
    'Gestión académica' => [
        'order' => 1,
        'items' => [
            'Asignaturas' => [
                'icon' => '<i class="far fa-books"></i>',
                'link' => [
                    'controller' => 'Subjects',
                    'action' => 'index',
                    'plugin' => 'Colmena/AcademicalManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false
                ],
            ],
            'Proyectos' => [
                'icon' => '<i class="far fa-project-diagram"></i>',
                'link' => [
                    'controller' => 'Projects',
                    'action' => 'index',
                    'plugin' => 'Colmena/AcademicalManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false
                ],
            ],
            'Años académicos' => [
                'icon' => '<i class="far fa-calendar"></i>',
                'link' => [
                    'controller' => 'AcademicalYears',
                    'action' => 'index',
                    'plugin' => 'Colmena/AcademicalManager',
                ],
                'extra' => [
                    'class' => 'menu-item',
                    'escape' => false
                ],
            ],
        ],
        'extra' => [
            'ico' => '<i class="fas fa-books"></i>',
        ],
    ],
]);

/*
 * Generate home page blocks
 */
Configure::write('Colmena/AcademicalManager.home_blocks', []);

/*
 * Set the parteable entities for this plugin
 */
// Configure::write('Colmena/AcademicalManager.parteable_entities', []);

/*
 * Set the searchable entities for this plugin
 */
Configure::write(
    'Colmena/AcademicalManager.searchable_entities',
    []
);

/*
 * Generate rolable entities array
 */
Configure::write('Colmena/AcademicalManager.rolable_entities', [
    'Subjects' => 'Asignaturas',
    'Projects' => 'Proyectos',
]);

/*
 * Special roles methods
 */
Configure::write('Colmena/AcademicalManager.special_method_entity_roles', []);

Configure::write('Colmena/AcademicalManager.api_entities', [
    'Colmena/AcademicalManager.Projects' => [
        'order' => 1,
    ],
    'Colmena/AcademicalManager.Subjects' => [
        'order' => 2,
    ],
]);

/*
 * Configure Products Manager API routing
 */
Configure::write('Colmena/AcademicalManager.routes', [
    '/:version/academic/:controller/:action' => [
        'defaults' => [
            'plugin' => 'Colmena/AcademicalManager',
        ],
        'options' => [],
    ],
]);
