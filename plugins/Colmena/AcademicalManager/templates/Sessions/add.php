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
                    'label' => 'Nombre de la sesión',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'weekday',
                [
                    'label' => 'Día de la semana',
                    'type' => 'int'
                ]
            ); ?>
            <?= $this->Form->control(
                'startHour',
                [
                    'label' => 'Hora de inicio de la sesión',
                    'type' => 'time'
                ]
            ); ?>
            <?= $this->Form->control(
                'endHour',
                [
                    'label' => 'Hora de fin de la sesión',
                    'type' => 'time'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <div class="primary full">
        <div class="form-block">
            <h3>Asignatura asociada</h3>
            <?= $this->Form->control(
                'subject_id',
                [
                    'label' => 'Asignatura',
                    'type' => 'text',
                    'value' => $subject->name,
                    'disabled' => 'disabled'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->