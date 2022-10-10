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
$header = [
    'title' => 'Visualizar ' . $entityName,
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
    <div class="primary">
        <div class="form-block">
            <h3>Datos generales</h3>
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
            <?php
            if (!isset($entity->session_id)) {
                echo $this->Form->control(
                    'session_id',
                    [
                        'label' => 'ID de la sesión',
                        'options' => $sessions,
                        'empty' => '---- Selecciona el ID de la sesión ----',
                        'templateVars' => [
                            'help' => 'Selecciona el ID de la sesión'
                        ]
                    ]
                );
            } else {
                echo $this->Form->control(
                    'session_id',
                    [
                        'label' => 'Sesión',
                        'type' => 'text',
                        'disabled' => true
                    ]
                );
            }
            ?>
        </div><!-- .form-block -->

    </div><!-- .primary -->
    <div class="secondary">
        <div class="table">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Nombre</th><!-- .th -->
                        <th scope="col">Apellidos</th><!-- .th -->
                        <th scope="col">UO</th><!-- .th -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="col">
                            <a href="/admin/users-manager/users/edit/<?= $entity->user_id ?>" class="user"><?= $entity->student->name ?></a>
                        </th>

                        <td scope="col">
                            <?= $entity->student->surname . ' ' . $entity->student->surname2 ?>
                        </td><!-- .td -->

                        <td scope="col">
                            <?= $entity->student->identifier ?>
                        </td><!-- .td -->
                    </tr><!-- .tr -->
                </tbody>
            </table>
        </div>

        <div class="form-block">
            <h3>Código erróneo</h3>
            <div class="flex-inputs">
                <?= $this->Form->control(
                    'snippet',
                    [
                        'label' => 'Código de error',
                        'type' => 'textarea',
                        'class' => 'codeeditor',
                    ]
                ); ?>
            </div>
        </div>
    </div><!-- .form-block -->
    <?= $this->element("form/save-block"); ?>
    <?= $this->Form->end(); ?>
    <?= $this->element('form/codeeditor-scripts'); ?>
</div><!-- .content -->