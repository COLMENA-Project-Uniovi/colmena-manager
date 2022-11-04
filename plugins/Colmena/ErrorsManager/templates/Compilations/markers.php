<?php

use Cake\Utility\Inflector;

$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);

$this->Breadcrumbs->add('Markers', [
    'controller' => 'Markers',
    'action' => 'index'
]);

$header = [
    'title' => 'Markers de la compilación',
    'breadcrumbs' => true
];

$tableButtons = [
    'Editar' => [
        'icon' => '<i class="fal fa-edit"></i>',
        'url' => [
            'controller' => 'Markers',
            'action' => 'edit',
            'plugin' => 'Colmena/ErrorsManager'
        ],
        'options' => [
            'class' => 'green-icon',
            'escape' => false
        ]
    ],
    'Borrar' => [
        'icon' => '<i class="fal fa-trash-alt"></i>',
        'url' => [
            'controller' => 'Markers',
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
                <table>
                    <thead>
                        <tr>
                            <th>Usuario</th><!-- .th -->
                            <th>Mensaje</th><!-- .th -->
                            <th>Género</th><!-- .th -->
                            <th>Fecha de creación</th><!-- .th -->
                            <th>Sesión</th><!-- .th -->
                            <?php
                            if (!empty($tableButtons)) {
                            ?>
                                <th class="actions">Operaciones</th><!-- .th -->
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($entities as $entity) {
                            $msg = isset($entity->session) ? $entity->session->name : '-- Sin asignar --'
                        ?>
                            <tr>
                                <th>
                                    <?php
                                    if ($entity->user_id != 0) {
                                        $name = $entity->student->name != '' && $entity->student->name != '-' ? $entity->student->name . ' ' . $entity->student->surname . ' ' . $entity->student->surname2 : $entity->student->identifier;
                                    ?>
                                        <a href="/admin/users-manager/users/edit/<?= $entity->user_id ?>" class="user"><?= $name ?></a>
                                    <?php
                                    }
                                    ?>
                                </th>

                                <td>
                                    <?= $entity->message; ?>
                                </td><!-- .td -->

                                <td>
                                    <?= $entity->gender; ?>
                                </td><!-- .td -->

                                <td>
                                    <?= $entity->timestamp; ?>
                                </td><!-- .td -->

                                <td>
                                    <?= $msg; ?>
                                </td><!-- .td -->

                                <?php
                                if (!empty($tableButtons)) {
                                ?>
                                    <td class="actions">
                                        <div class="td-content">
                                            <?php
                                            foreach ($tableButtons as $key => $value) {
                                                array_push($value['url'], $entity->id);
                                            ?>
                                            <?php
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
                    </tbody>
                </table>
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