<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Editar usuario "' . $entity->username . '"', [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Editar ' . $entity->username,
    'breadcrumbs' => true,
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
                    'type' => 'password',
                    'minLength' => 6,
                    'value' => '********',
                    'required' => false,
                    'templateVars' => [
                        'help' => 'Si realiza cambios en este campo, la contraseña de acceso del usuario a la herramienta de administración será modificada.'
                    ]
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
