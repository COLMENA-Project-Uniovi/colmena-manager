<?php
use Cake\Utility\Inflector;
use Cake\I18n\I18n;

$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityName), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$header = [
    'title' => ucfirst($entityNamePlural),
    'breadcrumbs' => true,
    'tabs' => $tabActions,
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
        if (!empty($entities->toArray())) {
    ?>
        <div class="top-results">
            <div class="num-results">
                <?= $this->element('paginator'); ?>
                <?= $this->Paginator->counter('<span>Mostrando {{start}}-{{end}} de {{count}} elementos</span>'); ?>
            </div><!-- .num-results -->
        </div><!-- .top-results -->
        <div class="table-responsive">
            <div class="thead">
                <div class="tr">
                    <div class="th grow">
                        Nombre
                    </div>
                    <div class="th medium-left">
                        Entidad
                    </div>
                    <div class="th medium-left">
                        Acción
                    </div>
                    <div class="th boolean">
                        Activo
                    </div>
                <?php
                    if (!empty($table_buttons)) {
                ?>
                    <div class="th actions">
                        Operaciones
                    </div>
                <?php
                    }
                ?>
                </div><!-- .tr -->
            </div><!-- .thead -->
            <div class="tbody elements small-placeholder">
        <?php
            foreach ($entities as $entity) {
        ?>
                <div class="tr">
                    <div class="td grow">
                        <?= $entity->name; ?>
                    </div><!-- .td -->
                    <div class="td medium-left">
                        <?= $entity->model; ?>
                    </div><!-- .td -->
                    <div class="td medium-left">
                        <?= $entity->action; ?>
                    </div><!-- .td -->

                    <div class="td boolean">
                    <?php
                        $checked = "";
                        if ($entity->active) {
                            $checked = "checked";
                        }
                    ?>
                        <p class="check <?= $checked; ?>"
                            data-id="<?= $entity->id; ?>"
                            data-controller="<?= Inflector::dasherize($this->request->getParam('controller')) ?>"
                            data-field="active"
                            data-plugin="false"
                            data-lang="<?= I18n::getLocale(); ?>">
                            <?= $this->Html->image('style/check.png'); ?>
                        </p>
                    </div><!-- .td -->
                    <?php
                        if (!empty($table_buttons)) {
                    ?>
                        <div class="td actions">
                            <div class="td-content">
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
                            </div><!-- .td-content -->
                        </div><!-- .td -->
                            <?php
                        }
                        ?>
                    </div><!-- .tr -->
            <?php
                }
            ?>
            </div><!-- .tbody -->
        </div><!-- .table -->
    <?php
        } else {
    ?>
        <p class="no-results">No existen resultados para la búsqueda realizada</p>
    <?php
        }
    ?>
    </div><!-- .results -->
</div><!-- .content -->
