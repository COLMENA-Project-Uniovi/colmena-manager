<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($displayNamePlural), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$this->Breadcrumbs->add('Importar ' . $displayNamePlural, [
    'controller' => $this->request->getParam('controller'),
    'action' => 'import'
]);
$header = [
    'title' => 'Importar ' . $displayNamePlural,
    'breadcrumbs' => true
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
        );
    ?>
        <div class="primary full">
            <div class="form-block">
                <h3>Selección de fichero</h3>
                <p>El fichero CSV debe contener los siguientes campos, separados por coma (","):</p>
                <div><ul>

                <?php
                    foreach($import_fields as $key => $value) {
                        if(strpos($value, "relations") === false){
                ?>
                            <li><?= $value ?></li>
                <?php
                        }
                    }
                ?>
                </ul></div>
                <?= $this->Form->control(
                    'file',
                    [
                        'label' => 'Selecciona un archivo CSV',
                        'type' => 'file'
                    ]
                ); ?>
            </div><!-- .form-block -->
        </div><!-- .primary -->
        <div class="save-block form-block">
            <?= $this->Html->link(
                'Cancelar',
                [
                    'action' => 'index',
                ],
                [
                    'class' => 'cancel',
                    'confirm' => '¿Estás seguro de que quieres descartar todos los cambios?'
                ]
            ); ?>
            <?= $this->Form->submit(
                'Importar CSV',
                [
                    'class' => 'button'
                ]
            ); ?>
        </div><!-- .form-block -->
    <?= $this->Form->end() ?>
    </div><!-- .content -->
