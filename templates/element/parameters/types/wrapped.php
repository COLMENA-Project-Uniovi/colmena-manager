<?php 


use Cake\Core\Configure;

?>
<style>
/* HIDE RADIO */
[type=radio] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* IMAGE STYLES */
[type=radio] + img {
  cursor: pointer;
}

/* CHECKED STYLES */
[type=radio]:checked + img {
  outline: 2px solid #f00;
}
.radio-label{
    width: 9%;
}
</style>
<p class="parameters-block-list__title">Selecciona la columna donde quieras que se inicie la sección</p>
<div class="options-wrapped" style="display: flex; flex-wrap: wrap; margin-bottom: 20px;">
<?php 

$parameters = $entity->parameter ? json_decode($entity->parameter->content) : (object) [];

echo $this->Form->input('wrappedFrom', [
        'templates' => [
            'nestingLabel' => '<label class="radio-label"{{attrs}}>{{input}}<img src="'.Configure::read("Config.base_url").'{{text}}"></label>'
        ],
        'type' => 'radio',
        'options' => $default_classes['wrapped']['from'],
        'label' => false,
        'value' => isset($parameters->wrappedFrom) ? $parameters->wrappedFrom : '',
    ]);

?>
</div>
<p class="parameters-block-list__title">Selecciona la columna donde quieras que finalice la sección</p>
<div class="options-wrapped" style="display: flex; flex-wrap: wrap;">
<?php 
echo $this->Form->input('wrappedTo', [
    'templates' => [
        'nestingLabel' => '<label class="radio-label"{{attrs}}>{{input}}<img src="'.Configure::read("Config.base_url").'{{text}}"></label>'
    ],
    'type' => 'radio',
    'options' => $default_classes['wrapped']['to'],
    'label' => false,
    'value' => isset($parameters->wrappedTo) ? $parameters->wrappedTo : '',
]);

?>

</div>