<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);

$this->Breadcrumbs->add('Editar ' . $entity_name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);

$header = [
    'title' => 'Editar ' . $entity_name,
    'breadcrumbs' => true,
    'tabs' => $tab_actions,
    'header' => [
        'actions' => [
            'Añadir sesión' => [
                'url' => [
                    'controller' => 'Sessions',
                    'plugin' => 'Colmena/AcademicalManager',
                    'action' => 'add',
                    $entity->id
                ]
            ],
            'Ver sesiones' => [
                'url' => [
                    'controller' => 'Sessions',
                    'plugin' => 'Colmena/AcademicalManager',
                    'action' => 'index',
                    $entity->id
                ]
            ]
        ]
    ]
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
                    'label' => 'Nombre',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'description',
                [
                    'label' => 'Descripción',
                    'type' => 'textarea'
                ]
            ); ?>
            <?= $this->Form->control(
                'year',
                [
                    'label' => 'Año académico',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'semester',
                [
                    'label' => 'Semestre',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'project_id',
                [
                    'label' => 'Proyecto',
                    'type' => 'number',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'start_date',
                [
                    'label' => 'Fecha de inicio',
                    'type' => 'date'
                ]
            ); ?>
            <?= $this->Form->control(
                'end_date',
                [
                    'label' => 'Fecha de fin',
                    'type' => 'date'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->