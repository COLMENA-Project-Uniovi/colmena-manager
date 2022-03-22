<?php 

use Cake\Core\Configure;

?>

<?= $this->Form->control(
    $config['key'], $config
); ?>

<?php
if($config['key'] == 'classes' && isset($default_classes) && $default_classes){
?>
    <div class="parameters-class-previsualization">
<?php
    // foreach($default_classes as $class => $value) {
?>
        <!-- <img class="parameters-class-previsualization__image" src="<?= $value['img'] ?>" data-class="<?= $class ?>" /> -->
<?php
    // }
?>
    </div>
    
<?php
}
