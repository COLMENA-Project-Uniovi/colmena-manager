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
                        <th>Nombre</th><!-- .th -->
                        <th>
                            Año académico
                        </th><!-- .th -->
                        <th>
                            Semestre
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
                <tbody class="tbody elements">
                    <?php
                    foreach ($entities as $entity) {
                        $tableButtons['Sesiones'] =
                            [
                                'icon' => '<i class="far fa-calendar-alt"></i>',
                                'url' => [
                                    'controller' => 'Sessions',
                                    'action' => 'index',
                                    'plugin' => 'Colmena/AcademicalManager',
                                ],
                                'options' => [
                                    'escape' => false
                                ]
                            ];
                    ?>
                        <tr>
                            <td>
                                <p><?= $entity->name ?></p>
                            </td><!-- .td -->
                            <td>
                                <p><?= $entity->academical_year_id != 0 ? $entity->academical_year->title : ''; ?></p>
                            </td><!-- .td -->
                            <td>
                                <p><?= $entity->semester; ?></p>
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
        <?php
        } else {
        ?>
            <p class="no-results">No existen resultados para la búsqueda realizada</p>
        <?php
        }
        ?>
    </div><!-- .results -->
</div><!-- .content -->