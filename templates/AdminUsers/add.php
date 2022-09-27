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
    'title' => 'Añadir ' . $entityName,
    'breadcrumbs' => true
];
?>

<?= $this->element("header", $header); ?>

<div class="content m-4">
<?= $this->Form->create(
    $entity,
    [
        'class' => 'admin-form',
        'type' => 'file'
    ]
); ?>
    <div class="primary full">
        <div class="form-block">
            <h3>Datos generales del <?= $entityName ?></h3>
            <?= $this->Form->control(
                'username',
                [
                    'label' => 'Nombre de usuario',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'password',
                [
                    'label' => 'Contraseña',
                    'minLength' => 6,
                    'type' => 'password'
                ]
            ); ?>
            <?= $this->Form->control(
                'password_repeat',
                [
                    'label' => 'Repetir contraseña',
                    'type' => 'password',
                    'required' => false,
                    'templateVars' => [
                        'help' => 'Repite la contraseña que has introducido anteriormente. Si dejas el campo anterior en blanco, no es necesario que introduzcas nada.',
                    ],
                ]
            ); ?>
            <?= $this->Form->control(
                'restaurant_id',
                [
                    'label' => 'Sidrería',
                    'options' => $restaurants,
                    'empty' => 'Selecciona la sidreria a la que tendrá acceso',
                    'required' => false,
                ]
            ); ?>
            <?= $this->Form->control(
                'role_id',
                [
                    'label' => 'Rol de usuario',
                    'options' => $roles,
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
<?= $this->Form->end() ?>
</div><!-- .content -->
