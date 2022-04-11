<?php
$class = 'notification';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div class="<?= h($class) ?>">
    <span class="close"><i class="fas fa-times"></i></span>
    <div class="nt-content"><?= h($message) ?></div>
</div><!-- .<?= h($class) ?> -->
