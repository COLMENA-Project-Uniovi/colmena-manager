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
                <thead class="thead">
                    <tr class="tr">
                        <th class="th medium">
                            Nombre
                        </th><!-- .th -->
                        <th class="th grow">
                            Año académico
                        </th><!-- .th -->
                        <th class="th grow">
                            Semestre
                        </th><!-- .th -->
                        <th class="th grow">
                            Fecha de inicio
                        </th><!-- .th -->
                        <th class="th grow">
                            Fecha de fin
                        </th><!-- .th -->
                        <?php
                        if (!empty($tableButtons)) {
                        ?>
                            <th class="th actions short">
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
                        <tr class="tr">
                            <td class="td element medium">
                                <p><?= $entity->name ?></p>
                            </td><!-- .td -->
                            <td class="td element grow">
                                <p><?= $entity->academical_year_id != 0 ? $entity->academical_year->title : ''; ?></p>
                            </td><!-- .td -->
                            <td class="td element grow">
                                <p><?= $entity->semester; ?></p>
                            </td><!-- .td -->
                            <td class="td element grow">
                                <p><?= $entity->start_date; ?></p>
                            </td><!-- .td -->
                            <td class="td element grow">
                                <p><?= $entity->end_date; ?></p>
                            </td><!-- .td -->
                            <?php
                            if (!empty($tableButtons)) {
                            ?>
                                <td class="td actions">
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