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
            <table class="table">
                <thead class="thead">
                    <tr>
                        <th class="grow">
                            Identificador
                        </th><!-- .th -->
                        <th class="medium">
                            Nombre y apellidos
                        </th><!-- .th -->
                        <th class="grow">
                            Email
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
                    ?>
                        <tr>
                            <td class="element grow">
                                <p><?= $entity->identifier; ?></p>
                            </td><!-- .td -->
                            <td class="element medium">
                                <p><?= $entity->name . ' ' . $entity->surname . ' ' . $entity->surname2; ?></p>
                            </td><!-- .td -->
                            <td class="element grow">
                                <p><?= $entity->email; ?></p>
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

    <?= $this->element('paginator'); ?>
</div><!-- .content -->