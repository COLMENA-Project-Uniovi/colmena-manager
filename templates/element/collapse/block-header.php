<div class="collapse-header">
    <?= $this->element(
        'form/block-header',
        [
            'title' => $title,
            'help' => isset($help)? $help: '',
            'aux' => isset($aux)? $aux: '',
            'position' => isset($position)? $position: ''
        ]
    ); ?>
    <div class="caret">
        <i class="fas fa-caret-right fa-lg"></i>
    </div><!-- .caret -->
</div><!-- .collapse-header -->
