<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Añadir ' . $entity_name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Añadir ' . $entity_name,
    'breadcrumbs' => true
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
            <?= $this->Form->control(
                'role_id',
                [
                    'label' => 'Rol',
                    'options' => $roles,
                    'empty' => 'Selecciona el rol del usuario',
                    'templateVars' => [
                        'help' => 'Selecciona el rol del usuario'
                    ]
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->