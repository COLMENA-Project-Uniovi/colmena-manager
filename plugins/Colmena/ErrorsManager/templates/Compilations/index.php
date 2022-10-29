<?php

use Cake\Utility\Inflector;

$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);

$header = [
    'title' => ucfirst($entityNamePlural),
    'breadcrumbs' => true,
    'header' => [
        'actions' => $header_actions,
        'search_form' => []
    ]
];

?>

<?= $this->element("header", $header); ?>
<?= $this->element('paginator'); ?>

<div class="content px-4">
    <div class="results">
        <?php
        if (count($entities) !== 0 && !empty($entities)) {
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th class="grow">
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
                            Fecha
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
                            <td class="element">
                                <p><?= $entity->student->name ?></p>
                            </td><!-- .td -->
                            <td class="element">
                                <p><?= $sessionName ?></p>
                            </td><!-- .td -->
                            <td class="element">
                                <p><?= $entity->type; ?></p>
                            </td><!-- .td -->
                            <td class="element">
                                <p><?= $entity->project_name; ?></p>
                            </td><!-- .td -->
                            <td class="element">
                                <p><?= $entity->timestamp; ?></p>
                            </td><!-- .td -->
                            <td class="element">
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
            <?= $this->element('paginator'); ?>
        <?php
        } else {
        ?>
            <p class="no-results">No existen resultados para la búsqueda realizada</p>
        <?php
        }
        ?>
    </div><!-- .results -->
</div><!-- .content -->