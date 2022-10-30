<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Visualizar ' . $entityName, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Visualizar ' . $entityName,
    'breadcrumbs' => true,
    'tabs' => $tabActions
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
    <div class="full">
        <div class="form-block">
            <h3>Datos generales</h3>
            <div class="flex-blocks two">
                <?= $this->Form->control(
                    'error_id',
                    [
                        'label' => 'Id del error',
                        'type' => 'text',
                    ]
                ); ?>
                <?= $this->Form->control(
                    'gender',
                    [
                        'label' => 'Género',
                        'type' => 'text',
                    ]
                ); ?>
            </div>
            <div class="flex-blocks two">
                <?= $this->Form->control(
                    'timestamp',
                    [
                        'label' => 'Fecha de creación',
                        'type' => 'text',
                    ]
                ); ?>
                <?= $this->Form->control(
                    'class_path',
                    [
                        'label' => 'Path de la clase',
                        'type' => 'text',
                    ]
                ); ?>
            </div>
            <div class="flex-blocks two">
                <?= $this->Form->control(
                    'project_path',
                    [
                        'label' => 'Path del proyecto',
                        'type' => 'text',
                    ]
                ); ?>
                <?= $this->Form->control(
                    'ip',
                    [
                        'label' => 'Dirección IP',
                        'type' => 'text',
                    ]
                ); ?>
            </div>
            <div class="flex-blocks two">

                <?= $this->Form->control(
                    'line_number',
                    [
                        'label' => 'Linea del error',
                        'type' => 'text',
                    ]
                ); ?>
                <?= $this->Form->control(
                    'message',
                    [
                        'label' => 'Mensaje del error',
                        'type' => 'text',
                    ]
                ); ?>
            </div>
            <div class="flex-blocks two">
                <?= $this->Form->control(
                    'session_id',
                    [
                        'label' => 'Sesión del marker',
                        'options' => $sessions,
                        'empty' => '---- Selecciona la sesión del marker ----',
                        'templateVars' => [
                            'help' => 'Selecciona la sesión del marker'
                        ]
                    ]
                );
                ?>

                <?= $this->Form->control(
                    'user_id',
                    [
                        'label' => 'Estudiante',
                        'options' => $students,
                        'empty' => '---- Selecciona el estudiante ----',
                        'templateVars' => [
                            'help' => 'Selecciona el estudiante'
                        ]
                    ]
                );
                ?>
            </div>
        </div><!-- .form-block -->

        <div class="form-block">
            <h3>Código del marcador</h3>
            <?= $this->Form->control(
                'source',
                [
                    'label' => 'Código del marker',
                    'type' => 'textarea',
                    'class' => 'codeeditor',
                    'help' => 'Introduce el código erróneo'
                ]
            ); ?>
        </div>
    </div>
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .primary -->

<?= $this->element('form/codeeditor-scripts'); ?>
</div><!-- .content -->