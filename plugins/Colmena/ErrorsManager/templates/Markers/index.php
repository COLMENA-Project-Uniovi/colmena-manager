<?php
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

<div class="content m-4">
    <div class="results">
        <?php
        if (count($entities) !== 0 && !empty($entities)) {
        ?>
            <div>
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Usuario</th><!-- .th -->
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
                            $msg = isset($entity->session) ? $entity->session->name : '-- Sin asignar --'
                        ?>
                            <tr>
                                <th scope="row">
                                    <?php
                                    if ($entity->user_id != 0) {
                                        $name = $entity->student->name != '' && $entity->student->name != '-' ? $entity->student->name . ' ' . $entity->student->surname . ' ' . $entity->student->surname2 : $entity->student->identifier;
                                    ?>
                                        <a href="/admin/users-manager/users/edit/<?= $entity->user_id ?>" class="user"><?= $name ?></a>
                                    <?php
                                    }
                                    ?>
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
        <?php
        } else {
        ?>
            <p class="no-results">No existen resultados para la búsqueda realizada</p>
        <?php
        }
        ?>
    </div><!-- .results -->

    <?= $this->element('paginator'); ?>
</div><!-- .content -->