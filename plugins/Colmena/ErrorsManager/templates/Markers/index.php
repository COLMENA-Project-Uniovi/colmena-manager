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

<div class="content m-4">
    <div class="results">
        <?php
        if (count($entities) !== 0 && !empty($entities)) {
        ?>
            <div class="table-responsive py-5">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Id usuario</th><!-- .th -->
                            <th scope="col">Mensaje</th><!-- .th -->
                            <th scope="col">Género</th><!-- .th -->
                            <th scope="col">Id de sesión</th><!-- .th -->
                            <?php
                            if (!empty($table_buttons)) {
                            ?>
                                <th scope="col">Operaciones</th><!-- .th -->
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($entities as $entity) {
                            $msg = isset($entity->sesion_id) ? $entity->sesion_id : 'REVISAR'
                        ?>
                            <tr>
                                <th scope="row"><?= $entity->user_id; ?></th>
                                <td scope="col">
                                    <?= $entity->message; ?>
                                </td><!-- .td -->
                                <td scope="col">
                                    <?= $entity->gender; ?>
                                </td><!-- .td -->
                                <td scope="col">
                                    <?= $msg; ?>
                                </td><!-- .td -->
                                <?php
                                if (!empty($table_buttons)) {
                                ?>
                                    <td class="actions" scope="col">
                                        <div class="td-content">
                                            <?php
                                            foreach ($table_buttons as $key => $value) {
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