<?php

use Cake\I18n\Time;

$this->Breadcrumbs->add('Inicio', '/');

$this->Breadcrumbs->add($subject->name, [
    'controller' => 'Subjects',
    'action' => 'edit', $subject->id
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
                    'label' => 'Nombre de la sesión',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'objective',
                [
                    'label' => 'Objetivo de la sesión',
                    'required' => true,
                    'type' => 'textarea',
                    'templateVars' => [
                        'max' =>  200
                    ]
                ]
            ); ?>

            <?= $this->Form->control(
                'language_id',
                [
                    'label' => 'Lenguaje de programación',
                    'options' => $programmingLanguages,
                    'empty' => '---- Selecciona el lenguaje de programación ----',
                    'templateVars' => [
                        'help' => 'Selecciona el lenguaje de programación'
                    ]
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