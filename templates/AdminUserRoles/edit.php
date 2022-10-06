<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Editar rol de usuario "' . $entity->name . '"', [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Editar ' . $entity->name,
    'breadcrumbs' => true,
    'tabs' => $tabActions
];
?>

<?= $this->element("header", $header); ?>

<div class="content px-4">
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
                'name',
                [
                    'label' => 'Nombre del rol',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'is_admin',
                [
                    'label' => '¿Es administrador?',
                    'type' => 'checkbox'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
<?= $this->Form->end() ?>
</div><!-- .content -->
