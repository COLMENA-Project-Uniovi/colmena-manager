<?php

use Cake\Core\Configure;

?>
<header class="home">
    <?= $this->Html->link(
        $this->Html->image(
            'logo-simple.svg',
            [
                'alt' => 'logo',
                'class' => 'logo-home'
            ]
        ),
        "/",
        ['escape' => false]
    ); ?>
    <h1>¡Bienvenido de nuevo <span style="font-weight: bold;"><?= $user['username'] ?></span>!</h1>
</header><!-- .home -->
<div class="blocks">
    <?php
    foreach ($home_blocks as $block) {
        echo $this->element($block['element'], $block);
    }
    ?>
</div><!-- .blocks -->