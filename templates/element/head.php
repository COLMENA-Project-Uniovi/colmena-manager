<?php

use Cake\Core\Configure;
?>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Administración <?= Configure::read('Project.name') ?>: <?= $this->fetch('title') ?>
    </title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <?php
    echo $this->Html->meta(
        'favicon.png',
        '/img/favicon.ico',
        ['type' => 'icon']
    );
    echo $this->fetch('meta');

    $this->Html->css('base', ['block' => 'default']);
    $this->Html->css('cake', ['block' => 'default']);

    // JQUERY
    $this->Html->css('style', ['block' => 'css']);

    $this->Html->script('vendors/jquery/jquery-3.3.1.min', ['block' => 'vendors']);
    $this->Html->script('vendors/jquery/jquery-ui-1.12.1/jquery-ui.min', ['block' => 'vendors']);

    // SELECT 2
    $this->Html->css('vendors/select2/select2.min', ['block' => 'vendors']);
    $this->Html->script('vendors/select2/select2.full.min', ['block' => 'vendors']);
    $this->Html->script('vendors/select2/select2_locale_es', ['block' => 'vendors']);

    //COLOR SELECTOR
    $this->Html->css("https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css", ['block' => 'vendors']);
    $this->Html->script("https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js", ['block' => 'vendors']);

    // MUSTACHE
    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.1.0/mustache.min.js', ['block' => 'vendors']);

    // BOOTSTRAP
    $this->Html->script('https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', ['block' => 'vendors']);
    $this->Html->script('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', ['block' => 'vendors']);

    // CHARTS
    $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js', ['block' => 'vendors']);

    // FUNCTIONS
    $this->Html->script('functions', ['block' => 'scripts']);

    $this->Html->css('https://pro.fontawesome.com/releases/v5.12.0/css/all.css', ['integrity' => 'sha384-ekOryaXPbeCpWQNxMwSWVvQ0+1VrStoPJq54shlYhR8HzQgig1v5fas6YgOqLoKz', 'crossorigin' => 'anonymous', 'block' => 'vendors']);

    $this->Html->css('https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css', ['block' => 'vendors']);
    $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js', ['block' => 'scripts']);

    echo $this->fetch('css');
    echo $this->fetch('plugin-css');
    echo $this->fetch('vendors');
    echo $this->fetch('scripts');
    ?>

    <!-- TINYMCE -->
    <script src="https://cdn.tiny.cloud/1/4ephn0sricfd36zvlngpa88irhicx0tl8yj47ie2fa3xixaq/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- JS CONSTANS DEFINITIONS -->
    <script>
        const ADMIN_PATH = '<?= $this->Url->build('/', ['fullBase' => true]); ?>';
        const TEMPLATES_PATH = ADMIN_PATH + 'webroot/js/templates/';
        const COLORS = <?= json_encode(Configure::read('UI.colors_bg')); ?>;
        const TEXTCOLOR_MAP = <?= json_encode(Configure::read('UI.colors_ft')); ?>;
        const TYPOGRAPHY = <?= json_encode(Configure::read('UI.typography')); ?>;
    </script>
</head>