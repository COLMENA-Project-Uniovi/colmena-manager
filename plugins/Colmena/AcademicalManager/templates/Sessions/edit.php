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

$this->Breadcrumbs->add('Editar ' . $entity->name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add', $subject->id
]);

$tabActions = [
    'Datos de la sesión' => [
        'url' => [
            'controller' => 'Sessions',
            'action' => 'edit/' . $entity->id . '/' . $subject->id,
            'plugin' => 'Colmena/AcademicalManager'
        ],
        'current' => 'current'
    ],
    'Horarios y grupos de la sesión' => [
        'url' => [
            'controller' => 'SessionSchedules',
            'action' => 'index/' . $entity->id . '/' . $subject->id,
            'plugin' => 'Colmena/AcademicalManager'
        ],
        'current' => ''
    ],
];

$header = [
    'title' => 'Editar ' . $entity->name,
    'breadcrumbs' => true,
    'tabs' => $tabActions
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
                    'required' => true,
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
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->