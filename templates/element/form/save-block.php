<?php
$save_message = isset($save_message)? $save_message: '';
?>
<div class="form-block save-block">
    <span><?= $save_message; ?></span>
    <?= $this->Html->link(
        'Volver',
        $this->request->referer(),
        [
            'class' => 'cancel',
            'confirm' => '¿Estás seguro de que quieres descartar todos los cambios?'
        ]
    ); ?>
    <?= $this->Form->submit(
        'Guardar',
        [
            'class' => 'button'
        ]
    ); ?>
</div><!-- .form-block -->
