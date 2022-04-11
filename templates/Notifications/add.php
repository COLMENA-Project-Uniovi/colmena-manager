<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Añadir ' . $entity_name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Añadir ' . $entity_name,
    'breadcrumbs' => true,
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
            <h3>Configuración de la <?= $entity_name ?></h3>
            <?= $this->Form->control(
                'name',
                [
                    'label' => 'Nombre',
                ]
            ); ?>
            <?= $this->Form->control(
                'plugin',
                [
                    'label' => 'Plugin',
                    'options' => $plugins
                ]
            ); ?>
            <?= $this->Form->control(
                'entity',
                [
                    'label' => 'Entidad'
                ]
            ); ?>
            <?= $this->Form->control(
                'action',
                [
                    'label' => 'Acción',
                ]
            ); ?>
            <?= $this->Form->control(
                'active',
                [
                    'label' => 'Activo',
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
<?= $this->Form->end() ?>
</div><!-- .content -->
