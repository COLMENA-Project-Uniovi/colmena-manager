<?php
/**
 * SEO configuration variables
 * Configure here all the fields we want for each SEO block
 */
return [
    'SEO' => [
        'general' => [
            'name' => 'General',
            'description' => 'Introduce el SEO genérico para este elemento.',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Título',
                    'max' => 65
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Descripción',
                    'max' => 300
                ],
                'folder' => [
                    'type' => 'text',
                    'label' => 'URL amigable',
                    'help' => 'Introduce la URL amigable de este elemento para mostrar en el navegador. El identificador debe ser único del tipo "elemento-de-prueba-sin-caracteres-extranos".',
                    'required' => true
                ]
            ],
            'fallbacks' => []
        ],
        'mobile' => [
            'name' => 'Móvil',
            'description' => 'Introduce el SEO para móviles de este elemento.',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Título',
                    'max' => 40
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Descripción',
                    'max' => 120
                ]
            ],
            'fallbacks' => []
        ],
        'og' => [
            'name' => 'Open Graph',
            'description' => 'Introduce el SEO para Facebook, LinkedIn y Google+.',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Título',
                    'max' => 65
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Descripción',
                    'max' => 155
                ],
                'image' => [
                    'type' => 'image',
                    'label' => 'Imagen 1200x630px'
                ]
            ],
            'fallbacks' => []
        ],
        'twitter' => [
            'name' => 'Twitter',
            'description' => 'Introduce el SEO para Twitter.',
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Título',
                    'max' => 70
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Descripción',
                    'max' => 200
                ],
                'card' => [
                    'type' => 'select',
                    'label' => 'Tipo de tarjeta',
                    'options' => [
                        'summary' => 'Tarjeta normal',
                        'summary_large_image' => 'Tarjeta con imagen grande'
                    ]
                ],
                'site' => [
                    'type' => 'text',
                    'label' => 'Sitio web que crea el contenido',
                    'help' => 'Introduce el usuario que crea el contenido. Ej: @username'
                ],
                'creator' => [
                    'type' => 'text',
                    'label' => 'Usuario que crea el contenido',
                    'help' => 'Introduce el usuario que crea el contenido. Ej: @username'
                ],
                'image' => [
                    'type' => 'image',
                    'label' => 'Imagen 1024x512px'
                ]
            ],
            'fallbacks' => []
        ],
        'metrics' => [
            'name' => 'Métricas',
            'description' => 'Introduce el código para métricas avanzadas o código especial.',
            'fields' => [
                'head-code' => [
                    'type' => 'textarea',
                    'class' => 'codeeditor',
                    'label' => 'Metaetiquetas y código auxiliar',
                    'help' => 'Introduce cualquier tipo de metaetiquetas generales u otro tipo de código que se colocará en la etiqueta &lt;head&gt;.'
                ]
            ]
        ]
    ]
];
