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
            <div class="table">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Id usuario</th><!-- .th -->
                            <th scope="col">Mensaje</th><!-- .th -->
                            <th scope="col">Género</th><!-- .th -->
                            <th scope="col">Fecha de creación</th><!-- .th -->
                            <th scope="col">Sesión</th><!-- .th -->
                            <?php
                            if (!empty($tableButtons)) {
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
                                <th scope="row">
                                    <a href="/admin/users-manager/users/edit/<?= $entity->user_id ?>" class="user"><?= $entity->user_id ?></a>
                                </th>

                                <td scope="col">
                                    <?= $entity->message; ?>
                                </td><!-- .td -->

                                <td scope="col">
                                    <?= $entity->gender; ?>
                                </td><!-- .td -->

                                <td scope="col">
                                    <?= $entity->timestamp; ?>
                                </td><!-- .td -->

                                <td scope="col">
                                    <?= $msg; ?>
                                </td><!-- .td -->

                                <?php
                                if (!empty($tableButtons)) {
                                ?>
                                    <td class="actions" scope="col">
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