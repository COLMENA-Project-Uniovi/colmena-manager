<?php
use Cake\Core\Configure;
?>
<header class="home">
    <h1>
        <?= $this->Html->link(
            $this->Html->image(
                'logo.png',
                [
                    'alt' => 'logo',
                    'style' => 'width:20%'
                ]
            ),
            "/" ,
            ['escape' => false]
        ); ?>
    </h1>
    <p>Portal de administración de la web de <?= Configure::read('Project.name') ?>.</p>
</header><!-- .home -->
<div class="blocks">
<?php
    foreach ($home_blocks as $block) {
        echo $this->element($block['element'], $block);
    }
?>
</div><!-- .blocks -->
