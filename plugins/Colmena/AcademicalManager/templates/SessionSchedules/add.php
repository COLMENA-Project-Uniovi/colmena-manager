<?php

use Cake\I18n\Time;

$this->Breadcrumbs->add('Inicio', '/');

$this->Breadcrumbs->add($subject->name, [
    'controller' => 'Subjects',
    'action' => 'edit', $subject->id
]);

$this->Breadcrumbs->add('Sesiones', [
    'controller' => 'Sessions',
    'action' => 'index',
    $subject->id
]);

$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index', $subject->id
]);

$this->Breadcrumbs->add('Añadir ' . $entityName, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add', $subject->id
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
                'date',
                [
                    'label' => 'Fecha de la sesión para el grupo',
                    'type' => 'date',
                    'templateVars' => [
                        'help' => 'Fecha de inicio de la sesión para el grupo'
                    ]
                ]
            ); ?>
            <?= $this->Form->control(
                'start_hour',
                [
                    'label' => 'Fecha de fin de la sesión para el grupo',
                    'type' => 'time'
                ]
            ); ?>

            <?= $this->Form->control(
                'end_hour',
                [
                    'label' => 'Fecha de fin de la sesión para el grupo',
                    'type' => 'time'
                ]
            ); ?>

            <?= $this->Form->control(
                'practice_group_id',
                [
                    'label' => 'Grupo de prácticas',
                    'multiple' => false,
                    'required' => true,
                    'empty' => '--- Selecciona el grupo del horario de la sesión ---',
                    'options' => $groups,
                    'templateVars' => [
                        'help' => 'Selecciona el grupo del horario de la sesión.'
                    ]
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <div class="primary full">
        <div class="form-block">
            <h3>Sesión asociada</h3>
            <?= $this->Form->control(
                'session_id',
                [
                    'label' => 'Sesión asignada',
                    'type' => 'text',
                    'value' => $session->name,
                    'disabled' => 'disabled'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->