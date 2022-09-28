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
<div class="content p-4">
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
                    'label' => 'Nombre del error',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'error_id',
                [
                    'label' => 'ID del error',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'wrong_start_line',
                [
                    'label' => 'Línea de inicio del error',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'wrong_end_line',
                [
                    'label' => 'Línea de fin del error',
                    'type' => 'number'
                ]
            ); ?>

            <?= $this->Form->control(
                'wrong_source_code',
                [
                    'label' => 'Código de error',
                    'type' => 'textarea'
                ]
            ); ?>

            <?= $this->Form->control(
                'right_start_line',
                [
                    'label' => 'Línea de inicio del error',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'right_end_line',
                [
                    'label' => 'Línea de fin del error',
                    'type' => 'number'
                ]
            ); ?>

            <?= $this->Form->control(
                'right_source_code',
                [
                    'label' => 'Código de error',
                    'type' => 'textarea'
                ]
            ); ?>

            <?= $this->Form->control(
                'explanation',
                [
                    'label' => 'Código de error',
                    'type' => 'textarea'
                ]
            ); ?>

            <?= $this->Form->control(
                'solution',
                [
                    'label' => 'Código de error',
                    'type' => 'textarea'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->