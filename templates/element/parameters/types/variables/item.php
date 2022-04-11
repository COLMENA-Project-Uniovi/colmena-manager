<div class="parameters-block-list__item parameters-variables__item flex-inputs two" data-item-id="0">
    <?= $this->Form->control(
        'variables[0][name]', 
        [
            'label' => 'Nombre',
            'type' => 'text'
        ]
    ); ?>
    <?= $this->Form->control(
        'variables[0][value]', 
        [
            'label' => 'Valor',
            'type' => 'text',
        ]
    ); ?>

    <span class="button parameters-block-list__remove-item"><i class="fas fa-trash"></i></span>
</div>