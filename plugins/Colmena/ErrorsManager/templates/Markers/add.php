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
    'title' => 'Añadir ' . $entityName,
    'breadcrumbs' => true
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
                    'label' => 'Nombre del estudiante',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'surname',
                [
                    'label' => 'Primer apellido del estudiante',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'surname2',
                [
                    'label' => 'Segundo apellido del estudiante',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'email',
                [
                    'label' => 'Email del estudiante',
                    'type' => 'email'
                ]
            ); ?>
            <?= $this->Form->control(
                'phone',
                [
                    'label' => 'Teléfono del estudiante',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'role_id.ids',
                [
                    'label' => 'Rol',
                    'options' => $roles,
                    'templateVars' => [
                        'help' => 'Selecciona el autor de la noticia'
                    ]
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->