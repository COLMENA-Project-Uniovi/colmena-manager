<?php
use Cake\I18n\I18n;
use Cake\Core\Configure;

$name = isset($name) ? $name : 'generic';
$default_locale = Configure::read('I18N.language');
$locale = I18n::getLocale();
$title = isset($title) ? $title : 'Dirección';
$help = isset($help) ? $help : '<p>Introduce la dirección completa y pulsa en el botón <strong>Buscar dirección en el mapa</strong> para obtener las coordenadas.</p><p>Para una ubicación más precisa, puedes arrastrar el marcador hasta la posición exacta donde se encuentra la dirección. Haz zoom para mejorar la precisión al mover el marcador.</p>';
$collapsable_class = isset($collapsable) && $collapsable === true ? 'collapsable' : '';
$collapse_block = isset($collapsable) && $collapsable === true ? 'collapse/block-header' : 'form/block-header';

if ($locale == $default_locale) {
    ?>
<div class="form-block address <?= $collapsable_class; ?>">
    <?= $this->element(
        $collapse_block,
        [
            'title' => $title,
            'help' => $help,
        ]
    ); ?>
    <div class="collapse">
        <div class="inputs">
            <?= $this->Form->control(
                'addresses.'.$name.'.address',
                [
                    'label' => 'Dirección',
                    'class' => 'addr',
                ]
            ); ?>
            <div class="flex-inputs two">
                <?= $this->Form->control(
                    'addresses.'.$name.'.city',
                    [
                        'label' => 'Población',
                        'class' => 'city',
                    ]
                ); ?>
                <?= $this->Form->control(
                    'addresses.'.$name.'.zip_code',
                    [
                        'label' => 'Código postal',
                        'class' => 'zipcode',
                    ]
                ); ?>
            </div><!-- .flex-inputs -->
            <div class="flex-inputs">
                <?= $this->Form->control(
                    'addresses.'.$name.'.region',
                    [
                        'label' => 'Provincia o región',
                        'class' => 'region',
                    ]
                ); ?>
                <?= $this->Form->control(
                    'addresses.'.$name.'.country',
                    [
                        'label' => 'País',
                        'class' => 'country',
                    ]
                ); ?>
            </div><!-- .flex-inputs -->
            <div class="flex-inputs">
                <?= $this->Form->control(
                    'addresses.'.$name.'.latitude',
                    [
                        'label' => 'Latitud',
                        'type' => 'text',
                        'class' => 'latitude',
                        'readonly' => true,
                    ]
                ); ?>
                <?= $this->Form->control(
                    'addresses.'.$name.'.longitude',
                    [
                        'label' => 'Longitud',
                        'type' => 'text',
                        'class' => 'longitude',
                        'readonly' => true,
                    ]
                ); ?>
            </div><!-- .flex-inputs -->
            <div class="locate-button">
                <div class="button address-search">Buscar dirección en el mapa</div>
            </div><!-- .locate-button -->
        </div><!-- .inputs -->
        <div class="map">
            <div class="map-canvas"></div>
        </div><!-- .map -->
    </div><!-- .collapse -->
</div><!-- .form-block -->
<?php
}
