<h3>
    <span class="title"><?= $title; ?></span>
<?php
if (isset($help) && $help != '') {
    ?>
    <div class="help <?= isset($position)? $position: ''; ?>">
        <i class="far fa-question-circle fa-lg"></i>
        <div class="info">
            <div><?= $help; ?></div>
        </div><!-- .info -->
    </div><!-- .help -->
    <?php
}
if (isset($aux) && $aux != '') {
    ?>
    <span class="aux">
        <?= $aux; ?>
    </span><!-- .aux -->
    <?php
}
?>
</h3>
