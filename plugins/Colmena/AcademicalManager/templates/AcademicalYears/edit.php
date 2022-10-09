<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entityNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);

$this->Breadcrumbs->add('Editar ' . $entityName, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);

$header = [
    'title' => 'Editar ' . $entityName,
    'breadcrumbs' => true,
    'tabs' => $tabActions
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
                'title',
                [
                    'label' => 'Nombre del curso académico',
                    'type' => 'text'
                ]
            ); ?>
            <?= $this->Form->control(
                'startDate',
                [
                    'label' => 'Fecha de inicio',
                    'type' => 'date'
                ]
            ); ?>
            <?= $this->Form->control(
                'endDate',
                [
                    'label' => 'Fecha de fin',
                    'type' => 'date'
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->