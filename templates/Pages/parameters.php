<?php
use Cake\I18n\I18n;

if(!isset($breadcrumbs) || !$breadcrumbs) {
    $this->Breadcrumbs->add('Inicio', '/');
    $this->Breadcrumbs->add(ucfirst($entityNamePlural), [
        'controller' => $this->request->getParam('controller'),
        'action' => 'index',
        I18n::getLocale()
    ]);
    $this->Breadcrumbs->add('Editar ' . $entityName . ' ' . $entity->name, [
        'controller' => $this->request->getParam('controller'),
        'action' => 'edit',
        $entity->id,
        I18n::getLocale()
    ]);
} else {
    $breadcrumbs($this->Breadcrumbs);
}

$header = [
    'title' => 'Parámetros de la '.$entityName,
    'breadcrumbs' => true,
    'tabs' => $tabActions,
];

?>
<?= $this->element('header', $header); ?>

<div class="content m-4">
<?= $this->Form->create(
    $entity,
    [
        'class' => 'admin-form',
        'type' => 'file',
    ]
); ?>
    <div class="parameters">
        <div class="parameters__buttons">
            <button class="button parameters__button parameters__button--visible" data-target="json">Ver JSON</button>
            <button class="button parameters__button" data-target="visual">Volver a la configuración visual</button>
        </div>
        <div class="parameters__wrapper">
            <div class="form-block parameters__item" data-type="json">
                <h3>JSON</h3>
                <?= $this->Form->control(
                    'parameter.content',
                    [
                        'label' => false,
                        'class' => 'codeeditor',
                        'data-mode' => 'javascript',
                        'data-height' => 600,
                    ]
                ); ?>
            </div>
            <div class="form-block parameters__item parameters__item--visible" data-type="visual">
                <h3>Configuración visual</h3>
                <div class="parameters__content">
                    <?php 
                        foreach($parametersConfig as $key => $item) {
                            echo $this->element('parameters/item', [ 
                                'item' => $item,
                                'key' => $key,
                                'defaultClasses' => isset($defaultClasses) ? $defaultClasses : []
                            ]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div><!-- .parameters -->
    <?= $this->element('form/codeeditor-scripts'); ?>
    <?= $this->element('form/save-block'); ?>
<?= $this->Form->end(); ?>
</div><!-- .content -->
