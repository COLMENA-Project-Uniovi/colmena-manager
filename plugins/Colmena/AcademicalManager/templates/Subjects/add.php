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
                    'label' => 'Nombre de la asignatura',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'description',
                [
                    'label' => 'Descripción de la asignatura',
                    'type' => 'textarea',
                    'templateVars' => [
                        'max' =>  200
                    ]
                ]
            ); ?>
            <?= $this->Form->control(
                'semester',
                [
                    'label' => 'Semestre de la asignatura',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'year',
                [
                    'label' => 'Año de la asignatura',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'year_id',
                [
                    'label' => 'Año académico',
                    'type' => 'text',
                    'value' => $academicalYears,
                    'disabled' => 'disabled'
                ]
            ); ?>
            <?= $this->Form->control(
                'start_date',
                [
                    'label' => 'Fecha de inicio de la asignatura',
                    'type' => 'date'
                ]
            ); ?>
            <?= $this->Form->control(
                'end_date',
                [
                    'label' => 'Fecha de fin de la asignatura',
                    'type' => 'date'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <div class="primary full">
        <div class="form-block">
            <h3>Proyecto asociado</h3>
            <?= $this->Form->control(
                'project_id',
                [
                    'label' => 'Proyecto',
                    'type' => 'text',
                    'value' => $project,
                    'disabled' => 'disabled'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->