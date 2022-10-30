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
    'Ver markers' => [
        'url' => [
            'controller' => 'Compilations',
            'plugin' => 'Colmena/ErrorsManager',
            'action' => 'see',
            $entity->id
        ]
    ]
];

$header = [
    'title' => 'Editar ' . $entityName,
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
                'user_id',
                [
                    'label' => 'Estudiante',
                    'options' => $students,
                    'empty' => '---- Selecciona el estudiante que produjo este error ----',
                    'templateVars' => [
                        'help' => 'Selecciona el estudiante que produjo este error'
                    ]
                ]
            ); ?>

            <?= $this->Form->control(
                'type',
                [
                    'label' => 'Tipo de compilación',
                    'type' => 'text'
                ]
            ); ?>

            <?php
            echo $this->Form->control(
                'session_id',
                [
                    'label' => 'Sesión',
                    'options' => $sessions,
                    'empty' => '---- Selecciona la sesión ----',
                    'templateVars' => [
                        'help' => 'Selecciona la sesión'
                    ]
                ]
            );
            ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>

    <?= $this->Form->end(); ?>
</div><!-- .content -->