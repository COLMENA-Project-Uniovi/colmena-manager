<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name_plural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Visualizar ' . $entity_name, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'add'
]);
$header = [
    'title' => 'Visualizar ' . $entity_name,
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
            <h3>Datos generales</h3>
            <?= $this->Form->control(
                'user_id',
                [
                    'label' => 'Id del estudiante',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'error_id',
                [
                    'label' => 'Id del error',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'gender',
                [
                    'label' => 'Género',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'timestamp',
                [
                    'label' => 'Fecha de creación',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'class_path',
                [
                    'label' => 'Path de la clase',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'project_path',
                [
                    'label' => 'Path del proyecto',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'ip',
                [
                    'label' => 'Dirección IP',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'line_number',
                [
                    'label' => 'Linea del error',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'message',
                [
                    'label' => 'Mensaje del error',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
            <?= $this->Form->control(
                'session_id',
                [
                    'label' => 'Sesión',
                    'type' => 'text',
                    'disabled' => true
                ]
            ); ?>
        </div><!-- .form-block -->
    </div><!-- .primary -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
</div><!-- .content -->