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

$header_actions = [
    'Ver ejemplos de este error' => [
        'url' => [
            'controller' => 'ErrorExamples',
            'plugin' => 'Colmena/ErrorsManager',
            'action' => 'index',
            $entity->error_id
        ]
    ]
];

$header = [
    'title' => 'Ver ' . $entityName,
    'breadcrumbs' => true,
    'tabs' => $tabActions,
    'header' => [
        'actions' => $header_actions,
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
                'error_id',
                [
                    'label' => 'Id del error',
                    'type' => 'number'
                ]
            ); ?>
            <?= $this->Form->control(
                'name',
                [
                    'label' => 'Nombre del error',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'message',
                [
                    'label' => 'Mensaje del error',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'family_id',
                [
                    'label' => 'Familia del error',
                    'options' => $families,
                    'empty' => '---- Selecciona la familia del error ----',
                    'templateVars' => [
                        'help' => 'Selecciona la familia del error'
                    ]
                ]
            ); ?>

            <?= $this->Form->control(
                'reference',
                [
                    'label' => 'Referencia del error',
                    'type' => 'textarea'
                ]
            ); ?>

            <?= $this->Form->control(
                'gender',
                [
                    'label' => 'Género del error',
                    'type' => 'text'
                ]
            ); ?>
            
            <?= $this->Form->control(
                'problema_reason',
                [
                    'label' => 'Razón del problema',
                    'type' => 'textarea',
                    'class' => 'texteditor',
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->