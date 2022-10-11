

<div class="parameters-block-list__item parameters-decoration-image__item" data-item-id="0">
    <span class="button parameters-block-list__remove-item"><i class="fas fa-trash"></i></span>
    <div class="flex-inputs parameters-decoration-image__path">
        <?= $this->element(
            "Colmena/MediaManager.media/unique-input",
            [
                'media_name' => 'decoration-image[0][path]',
                'config' => [
                    'name' => 'Imagen',
                    'custom' => true
                ],
            ]
        ); ?>
    </div>
    <div class="flex-inputs four">
        <?= $this->Form->control(
            'decoration-image[0][width]', 
            [
                'label' => 'Ancho',
                'type' => 'text'
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][height]', 
            [
                'label' => 'Alto',
                'type' => 'text'
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][transform]', 
            [
                'label' => 'Transformaciones (CSS)',
                'type' => 'text'
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][position]', 
            [
                'label' => 'Posicionamiento',
                'type' => 'text'
            ]
        ); ?>
    </div>
    
    <div class="flex-inputs two">
        <?= $this->Form->control(
            'decoration-image[0][classes]', 
            [
                'label' => 'Clases',
                'type' => 'select',
                'class' => 'keywords',
                'multiple' => true
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][zIndex]', 
            [
                'label' => 'z-index',
                'type' => 'number'
            ]
        ); ?>
         <?= $this->Form->control(
            'decoration-image[0][inverted]', 
            [
                'label' => 'Imagen invertida',
                'type' => 'checkbox'
            ]
        ); ?>
    </div>
    <div class="flex-inputs four">
        <?= $this->Form->control(
            'decoration-image[0][top]', 
            [
                'label' => 'Top',
                'type' => 'text',
                'value' => 'auto'
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][bottom]', 
            [
                'label' => 'Bottom',
                'type' => 'text',
                'value' => 'auto'
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][left]', 
            [
                'label' => 'Left',
                'type' => 'text',
                'value' => 'auto'
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][right]', 
            [
                'label' => 'Right',
                'type' => 'text',
                'value' => 'auto'
            ]
        ); ?>
    </div>
    <div class="flex-inputs four">
        <?= $this->Form->control(
            'decoration-image[0][margin-top]', 
            [
                'label' => 'Margen superior',
                'type' => 'text',
                'value' => 0
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][margin-bottom]', 
            [
                'label' => 'Margen inferior',
                'type' => 'text',
                'value' => 0
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][margin-left]', 
            [
                'label' => 'Margen izquierdo',
                'type' => 'text',
                'value' => 0
            ]
        ); ?>
        <?= $this->Form->control(
            'decoration-image[0][margin-right]', 
            [
                'label' => 'Margen derecho',
                'type' => 'text',
                'value' => 0
            ]
        ); ?>
    </div>
    <div class="parameters-decoration-image__css-editor">
        <?= $this->Form->control(
            'decoration-image[0][style]', 
            [
                'label' => 'Estilos CSS extra',
                'class' => 'codeeditor',
                'data-height' => 100,
                'type' => 'textarea'
            ]
        ); ?>
    </div>
</div>