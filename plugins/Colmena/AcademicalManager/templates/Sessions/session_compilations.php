<?php


$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add('Asignaturas', [
    'controller' => 'Subjects',
    'action' => 'index'
]);
$this->Breadcrumbs->add($subject->name, [
    'controller' => 'Subjects',
    'action' => 'edit', $subject->id
]);
$this->Breadcrumbs->add('Sesiones', [
    'controller' => 'Sessions',
    'action' => 'index',
    $subject->id
]);
$this->Breadcrumbs->add('Compilaciones', [
    'plugin' => 'Colmena/ErrorsManager',
    'controller' => 'Compilations',
    'action' => 'index'
]);

$header = [
    'title' => 'Compilaciones de la sesión',
    'breadcrumbs' => true,
];
$tableButtons = [
    'Editar' => [
        'icon' => '<i class="fal fa-edit"></i>',
        'url' => [
            'controller' => 'Compilations',
            'action' => 'edit',
            'plugin' => 'Colmena/ErrorsManager'
        ],
        'options' => [
            'class' => 'green-icon',
            'escape' => false
        ]
    ],
    'Borrar' => [
        'icon' => '<i class="fas fa-trash"></i>',
        'url' => [
            'controller' => 'Compilations',
            'action' => 'delete',
            'plugin' => 'Colmena/ErrorsManager'
        ],
        'options' => [
            'confirm' => '¿Estás seguro de que quieres eliminar El marker?',
            'class' => 'red-icon',
            'escape' => false
        ]
    ]
]
?>

<?= $this->element("header", $header); ?>

<div class="content p-4">
    <div class="results">
        <?php
        if (count($entities) !== 0 && !empty($entities)) {
        ?>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="medium">
                                Nombre del alumno
                            </th><!-- .th -->
                            <th class="grow">
                                Sesión
                            </th><!-- .th -->
                            <th class="grow">
                                Tipo
                            </th><!-- .th -->
                            <th class="grow">
                                Proyecto
                            </th><!-- .th -->
                            <th class="grow">
                                Número de markers
                            </th><!-- .th -->
                            <?php
                            if (!empty($tableButtons)) {
                            ?>
                                <th class="actions short">
                                    Operaciones
                                </th><!-- .th -->
                            <?php
                            }
                            ?>
                        </tr><!-- .tr -->
                    </thead><!-- .thead -->
                    <tbody class="elements">
                        <?php
                        foreach ($entities as $entity) {
                            $sessionName = $entity->session->name ?? '-- Sin asignar --';
                        ?>
                            <tr>
                                <td class="element medium">
                                    <p><?= $entity->student->name ?></p>
                                </td><!-- .td -->
                                <td class="element grow">
                                    <p><?= $sessionName ?></p>
                                </td><!-- .td -->
                                <td class="element grow">
                                    <p><?= $entity->type; ?></p>
                                </td><!-- .td -->
                                <td class="element grow">
                                    <p><?= $entity->project_name; ?></p>
                                </td><!-- .td -->
                                <td class="element grow">
                                    <p><?= $entity->num_markers; ?></p>
                                </td><!-- .td -->
                                <?php
                                if (!empty($tableButtons)) {
                                ?>
                                    <td class="actions">
                                        <div class="td-content">
                                            <?php
                                            foreach ($tableButtons as $key => $value) {
                                                array_push($value['url'], $entity->id);
                                                if ($value['url']['action'] != 'delete') {
                                                    echo $this->Html->link(
                                                        $value['icon'],
                                                        $value['url'],
                                                        $value['options']
                                                    );
                                                } else {
                                                    echo $this->Form->postLink(
                                                        $value['icon'],
                                                        $value['url'],
                                                        $value['options']
                                                    );
                                                }
                                            }
                                            ?>
                                        </div><!-- .td-content -->
                                    </td><!-- .td -->
                                <?php
                                }
                                ?>
                            </tr><!-- .tr -->
                        <?php
                        }
                        ?>
                    </tbody><!-- .tbody -->
                </table><!-- .table -->
            </div>
        <?php
        } else {
        ?>
            <p class="no-results">No existen resultados para la búsqueda realizada</p>
        <?php
        }
        ?>
    </div><!-- .results -->
</div><!-- .content -->