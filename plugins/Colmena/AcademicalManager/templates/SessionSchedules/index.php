<?php

use Cake\Utility\Inflector;

$this->Breadcrumbs->add('Inicio', '/');

$this->Breadcrumbs->add($subject->name, [
    'controller' => 'Subjects',
    'action' => 'edit', $subject->id
]);

$this->Breadcrumbs->add('Sesiones', [
    'controller' => 'Sessions',
    'action' => 'index',
    $subject->id
]);

$this->Breadcrumbs->add('Editar ' . $session->name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add',
    $session->id,
    $subject->id
]);

$tabActions = [
    'Datos de la sesión' => [
        'url' => [
            'controller' => 'Sessions',
            'action' => 'edit/' . $session->id . '/' . $subject->id,
            'plugin' => 'Colmena/AcademicalManager'
        ],
        'current' => ''
    ],
    'Horarios y grupos de la sesión' => [
        'url' => [
            'controller' => 'SessionSchedules',
            'action' => 'index/' . $session->id . '/' . $subject->id,
            'plugin' => 'Colmena/AcademicalManager'
        ],
        'current' => 'current'
    ],
];

$header = [
    'title' => ucfirst($entityNamePlural),
    'breadcrumbs' => true,
    'tabs' => $tabActions,
    'header' => [
        'actions' => [
            'Añadir horario y grupo' => [
                'url' => [
                    'controller' => 'SessionSchedules',
                    'plugin' => 'Colmena/AcademicalManager',
                    'action' => 'add',
                    $session->id,
                    $subject->id
                ]
            ]
        ]
    ]
];
?>

<?= $this->element("header", $header); ?>

<div class="content p-4">
    <div class="results">
        <?php
        if (count($entities) !== 0 && !empty($entities)) {
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th class="medium">
                            Id del Grupo
                        </th><!-- .th -->
                        <th class="medium">
                            Nombre del Grupo
                        </th><!-- .th -->
                        <th class="grow">
                            Fecha de inicio
                        </th><!-- .th -->
                        <th class="grow">
                            Fecha de fin
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
                    ?>
                        <tr>
                            <td class="element medium">
                                <p><?= $entity->practice_group_id ?></p>
                            </td><!-- .td -->
                            <td class="element medium">
                                <p><?= $entity->practice_group->name ?></p>
                            </td><!-- .td -->
                            <td class="element grow">
                                <p><?= $entity->start_hour; ?></p>
                            </td><!-- .td -->
                            <td class="element grow">
                                <p><?= $entity->end_hour; ?></p>
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
            <?= $this->element('paginator'); ?>
        <?php
        } else {
        ?>
            <p class="no-results">No existen horarios ni grupos asignados</p>
        <?php
        }
        ?>
    </div><!-- .results -->
</div><!-- .content -->