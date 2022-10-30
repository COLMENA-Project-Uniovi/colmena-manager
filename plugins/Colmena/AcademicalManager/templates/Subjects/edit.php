<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);

$this->Breadcrumbs->add('Editar ' . $entityName, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);

$header = [
    'title' => 'Editar ' . $entityName,
    'breadcrumbs' => true,
    'tabs' => $tabActions,
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
                'academical_year_id',
                [
                    'label' => 'Año académico',
                    'options' => $academicalYears,
                    'empty' => '---- Selecciona el año académico ----',
                    'templateVars' => [
                        'help' => 'Selecciona el año académico'
                    ]
                ]
            ); ?>

            <?= $this->Form->control(
                'semester',
                [
                    'label' => 'Semestre',
                    'type' => 'number'
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
                    'value' => $entity->project->name,
                    'disabled' => 'disabled'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->