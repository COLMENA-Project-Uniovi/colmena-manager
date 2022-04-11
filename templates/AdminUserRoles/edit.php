<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
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
    'tabs' => $tab_actions
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
            <h3>Datos generales del <?= $entity_name ?></h3>
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
