<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Editar ' . $entity_name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Editar ' . $entity_name,
    'breadcrumbs' => true,
    'tabs' => $tab_actions
];
?>

<?= $this->element("header", $header); ?>
<div class="content">
    <?= $this->Form->create(
        $entity,
        [
            'class' => 'admin-form',
            'type' => 'file'
        ]
    ); ?>
    <div class="primary full">
        <div class="form-block">
            <h3>Datos generales</h3>
            <?= $this->Form->control(
                'error_id',
                [
                    'label' => 'Id del error',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'name',
                [
                    'label' => 'Nombre del error',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'message',
                [
                    'label' => 'Mensaje del error',
                    'type' => 'email'
                ]
            ); ?>
            <?= $this->Form->control(
                'family_id',
                [
                    'label' => 'Familia del error',
                    'options' => $families,
                    'empty' => '---- Selecciona la familia del error ----',
                    'templateVars' => [
                        'help' => 'Selecciona la familia del error'
                    ]
                ]
            ); ?>

        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->