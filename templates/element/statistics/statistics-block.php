<div class="statistics-blocks">
    <div class="statistics-blocks__content">
        <?php
            foreach($blocks as $block) {
                echo $this->element('statistics/blocks/'.$block['type'], ['block' => $block]);
            }
        ?>
    </div>
</div>