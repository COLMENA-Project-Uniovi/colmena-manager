<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Editar ' . $entity_name . ' ' . $entity->name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'edit',
    $entity->id
]);
$header = [
    'title' => 'Editar ' . $entity_name . ' ' . $entity->name,
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
                    'label' => 'Nombre de la dirección',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'phone',
                [
                    'label' => 'Telefono'
                ]
            ); ?>
            <?= $this->Form->control(
                'email',
                [
                    'label' => 'Email principal',
                    'type' => 'email'
                ]
            ); ?>
        </div><!-- .form-block -->
        <?= $this->element("address-block"); ?>
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
<?= $this->Form->end() ?>
</div><!-- .content -->
