<?php
use Cake\Utility\Inflector;

$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$header = [
    'title' => ucfirst($entity_name_plural),
    'breadcrumbs' => true,
    'header' => [
        'actions' => $header_actions,
        'search_form' => []
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
                    <th class="sortable">
                        <?= $this->Paginator->sort(
                            'name',
                            'Nombre de la dirección'
                        );?>
                    </th>
                    <th class="boolean">
                        Principal
                    </th>
                    <th class="boolean">
                        Visible
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
                    <td class="element">
                        <p><?= $entity->name; ?></p>
                    </td>
                    <td class="boolean">
                    <?php
                        $checked = "";
                        if ($entity->is_default) {
                            $checked = "checked";
                        }
                    ?>
                        <p class="check <?= $checked; ?>"
                            data-id="<?= $entity->id; ?>"
                            data-controller="<?= Inflector::dasherize($this->request->getParam('controller')) ?>"
                            data-field="is_default">
                            <?= $this->Html->image('style/check.png'); ?>
                        </p>
                    </td>
                    <td class="boolean">
                    <?php
                        $checked = "";
                        if ($entity->is_visible) {
                            $checked = "checked";
                        }
                    ?>
                        <p class="check <?= $checked; ?>"
                            data-id="<?= $entity->id; ?>"
                            data-controller="<?= Inflector::dasherize($this->request->getParam('controller')) ?>"
                            data-field="is_visible">
                            <?= $this->Html->image('style/check.png'); ?>
                        </p>
                    </td>
                    <?php
                        if (!empty($table_buttons)) {
                    ?>
                        <td class="actions">
                        <?php
                            foreach($table_buttons as $key => $value) {
                                array_push($value['url'], $entity->id);
                                if($value['url']['action'] != 'delete') {
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
