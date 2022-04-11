<?php

$admin_path = isset($_GET['admin_path']) ? $_GET['admin_path'] : '';

$config = [
    [
        'title' => 'Botón',
        'description' => 'Enlace con estilo',
        'url' => $admin_path.'/js/vendors/tinymce/templates/button.html',
    ],
    [
        'title' => 'Dos columnas continuas',
        'description' => 'El alto de las columnas se adapta en función del contenido.',
        'url' => $admin_path.'/js/vendors/tinymce/templates/two-columns.html',
    ],
    [
        'title' => 'Tres columnas continuas',
        'description' => 'El alto de las columnas se adapta en función del contenido.',
        'url' => $admin_path.'/js/vendors/tinymce/templates/three-columns.html',
    ],
    [
        'title' => 'Cuatro columnas continuas',
        'description' => 'El alto de las columnas se adapta en función del contenido.',
        'url' => $admin_path.'/js/vendors/tinymce/templates/four-columns.html',
    ],
    [
        'title' => 'Dos columnas independientes',
        'description' => 'El alto de las columnas es independiente del resto.',
        'url' => $admin_path.'/js/vendors/tinymce/templates/two-flex-columns.html',
    ],
    [
        'title' => 'Tres columnas independientes',
        'description' => 'El alto de las columnas es independiente del resto.',
        'url' => $admin_path.'/js/vendors/tinymce/templates/three-flex-columns.html',
    ],
    [
        'title' => 'Cuatro columnas independientes',
        'description' => 'El alto de las columnas es independiente del resto.',
        'url' => $admin_path.'/js/vendors/tinymce/templates/four-flex-columns.html',
    ],
];

echo json_encode($config);
