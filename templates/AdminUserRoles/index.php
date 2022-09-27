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
    'tabs' => $tabActions,
    'header' => [
        'actions' => $header_actions
    ]
];
?>

<?= $this->element("header", $header); ?>
<div class="content">
    <div class="results">
    <?php
        if (!empty($entities->toArray())) {
    ?>
        <div class="top-results">
            <div class="num-results">
                <?= $this->element('paginator'); ?>
                <?= $this->Paginator->counter('<span>Mostrando {{start}}-{{end}} de {{count}} elementos</span>'); ?>
            </div><!-- .num-results -->
        </div><!-- .top-results -->
        <table>
            <thead>
                <tr>
                    <th class="sortable grow">
                        <?= $this->Paginator->sort(
                            'name',
                            'Nombre del rol'
                        );?>
                    </th>
                    <th class="boolean medium">
                        ¿Es administrador?
                    </th>
                <?php
                    if (!empty($table_buttons)) {
                ?>
                    <th class="actions short">
                        Operaciones
                    </th>
                <?php
                    }
                ?>
                </tr>
            </thead>
            <tbody>
        <?php
            foreach ($entities as $entity) {
        ?>
                <tr>
                    <td class="element grow">
                        <p><?= $entity->name; ?></p>
                    </td>
                    <td class="boolean medium">
                    <?php
                        $checked = "";
                        if ($entity->is_admin) {
                            $checked = "checked";
                        }
                    ?>
                        <p class="check <?= $checked; ?>"
                            data-id="<?= $entity->id; ?>"
                            data-controller="<?= Inflector::dasherize($this->request->getParam('controller')) ?>"
                            data-field="is_admin">
                            <?= $this->Html->image('style/check.png'); ?>
                        </p>
                    </td>
                    <?php
                        if (!empty($table_buttons)) {
                    ?>
                        <td class="actions">
                        <?php
                            foreach ($table_buttons as $key => $value) {
                                array_push($value['url'], $entity->id);
                                if ($value['url']['action'] != 'delete') {
                                    echo $this->Html->link(
                                        $key,
                                        $value['url'],
                                        $value['options']
                                    );
                                } else {
                                    echo $this->Form->postLink(
                                        $key,
                                        $value['url'],
                                        $value['options']
                                    );
                                }
                            }
                        ?>
                        </td>
                    <?php
                        }
                    ?>
                </tr>
        <?php
            }
        ?>
            </tbody>
        </table>
        <?= $this->element('paginator'); ?>
    <?php
        } else {
    ?>
        <p class="no-results">No existen resultados para la búsqueda realizada</p>
    <?php
        }
    ?>
    </div><!-- .results -->
</div><!-- .contact -->
