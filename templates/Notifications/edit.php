<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Editar ' . $entityName . ' ' . $entity->name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'edit',
    $entity->id
]);
$header = [
    'title' => 'Editar ' . $entityName . ' ' . $entity->name,
    'breadcrumbs' => true,

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
            <h3>Configuración de la <?= $entityName ?></h3>
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
                    'options' => $plugins,
                    'value' => explode(".", $entity['model'])[0]
                ]
            ); ?>
            <?= $this->Form->control(
                'entity',
                [
                    'label' => 'Entidad',
                    'value' => explode(".", $entity['model'])[1]
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
