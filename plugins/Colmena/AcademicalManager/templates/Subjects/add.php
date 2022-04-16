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
                    'label' => 'Nombre de la asignatura',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'description',
                [
                    'label' => 'Descripción de la asignatura',
                    'type' => 'text',
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
                'academical_year',
                [
                    'label' => 'Año académico de la asignatura',
                    'type' => 'number'
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
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->