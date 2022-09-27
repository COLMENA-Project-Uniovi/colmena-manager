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
    'title' => 'Editar ' . $entityName,
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
                'session_id',
                [
                    'label' => 'ID de la sesión',
                    'options' => $sessions,
                    'empty' => '---- Selecciona el ID de la sesión ----',
                    'templateVars' => [
                        'help' => 'Selecciona el ID de la sesión'
                    ]
                ]
            ); ?>
        </div><!-- .form-block -->

        <div class="flex-blocks two">
            <div class="form-block">
                <h3>Código erróneo</h3>
                <div class="flex-inputs two">
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
                </div>

                <?= $this->Form->control(
                    'wrong_source_code',
                    [
                        'label' => 'Código de error',
                        'type' => 'textarea',
                        'class' => 'codeeditor',
                        'help' => 'Introduce el código erróneo'
                    ]
                ); ?>
            </div>

            <div class="form-block">
                <h3>Solucion</h3>
                <div class="flex-inputs two">
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
                </div>

                <?= $this->Form->control(
                    'right_source_code',
                    [
                        'label' => 'Código de error',
                        'type' => 'textarea',
                        'class' => 'codeeditor',
                        'help' => 'Introduce el código correcto'
                    ]
                ); ?>
            </div>
        </div>

        <div class="form-block">
            <h3>Explicación y solución propuesta</h3>
            <?= $this->Form->control(
                'explanation',
                [
                    'label' => 'Explicación',
                    'type' => 'textarea',
                    'class' => 'texteditor'
                ]
            ); ?>

            <?= $this->Form->control(
                'solution',
                [
                    'label' => 'Solución',
                    'class' => 'codeeditor',
                    'type' => 'textarea'
                ]
            ); ?>
        </div>
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
    <?= $this->element('form/codeeditor-scripts'); ?>
</div><!-- .content -->