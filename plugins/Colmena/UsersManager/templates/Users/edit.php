<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Añadir ' . $entityName, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Editar ' . $entityName,
    'breadcrumbs' => true,
    'tabs' => $tabActions
];
?>

<?= $this->element("header", $header); ?>
<div class="content px-4">
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
                'name',
                [
                    'label' => 'Nombre del usuario',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'surname',
                [
                    'label' => 'Primer apellido del usuario',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'surname2',
                [
                    'label' => 'Segundo apellido del usuario',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'email',
                [
                    'label' => 'Email del usuario',
                    'type' => 'email'
                ]
            ); ?>
            <?= $this->Form->control(
                'phone',
                [
                    'label' => 'Teléfono del usuario',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'identifier',
                [
                    'label' => 'Identificador del usuario (UO)',
                    'type' => 'text'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->