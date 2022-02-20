<div class="statistics-block">
    <p class="statistics-block__title">
        <?= $block['title']; ?>
    </p>
    <canvas class="chart" data-chart-type="<?= $block['chart-type']; ?>" width="800" height="300" data-chart='<?= json_encode($block['value']); ?>'></canvas>
</div>