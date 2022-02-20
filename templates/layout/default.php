<?php
/**
 * @copyright     Copyright (c) Neozink
 * @link          http://www.neozink.com Neozink Mkt No Convencional
 * @since         Neozink(tm) v 0.0.2
 * @license       All Rights Reserved
 */

// Some usefull classes needed in default view
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Administración <?= Configure::read('Project.name') ?>: <?= $this->fetch('title') ?>
    </title>
    <?php
        echo $this->Html->meta(
            'favicon.png',
            '/img/favicon.ico',
            ['type' => 'icon']
        );
        echo $this->fetch('meta');

        $this->Html->css('vendors/jquery-ui-1.12.1/jquery-ui.min', ['block' => 'css']);
        $this->Html->css('base', ['block' => 'default']);
        $this->Html->css('cake', ['block' => 'default']);
        
        // JQUERY
        $this->Html->css('style', ['block' => 'css']);

        
        $this->Html->script('vendors/jquery/jquery-3.3.1.min', ['block' => 'vendors']);
        $this->Html->script('vendors/jquery/jquery-ui-1.12.1/jquery-ui.min', ['block' => 'vendors']);
        
        // FONT AWESOME
        $this->Html->script('https://kit.fontawesome.com/ff149dc327.js', ['block' => 'vendors']);

        // SELECT 2
        $this->Html->css('vendors/select2/select2.min', ['block' => 'vendors']);
        $this->Html->script('vendors/select2/select2.full.min', ['block' => 'vendors']);
        $this->Html->script('vendors/select2/select2_locale_es', ['block' => 'vendors']);

        // NEO MAPS
        $this->Html->script("https://maps.google.com/maps/api/js?key=AIzaSyDCj91m02CHBP0pFwEIqSYIWWMlmL4oTwc", ['block' => "vendors"]);
        $this->Html->script('vendors/maps/neo-maps-3.0', ['block' => 'vendors']);
        // TINYMCE
        $this->Html->script('vendors/tinymce/tinymce.min', ['block' => 'vendors']);
        $this->Html->script('vendors/tinymce/jquery.tinymce.min', ['block' => 'vendors']);
        $this->Html->script('vendors/tinymce/es', ['block' => 'vendors']);

        //COLOR SELECTOR
        $this->Html->css("https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css", ['block' => 'vendors']);
        $this->Html->script("https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js", ['block' => 'vendors']);

        // MUSTACHE
        $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.1.0/mustache.min.js', ['block' => 'vendors']);

        // CHARTS
        $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js', ['block' => 'vendors']);

        // FUNCTIONS
        $this->Html->script('functions', ['block' => 'scripts']);
        

        $this->Html->css('https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css', ['block' => 'vendors']);
        $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js', ['block' => 'scripts']);

        echo $this->fetch('css');
        echo $this->fetch('plugin-css');
        echo $this->fetch('vendors');
        echo $this->fetch('scripts');
    ?>
    <!-- JS CONSTANS DEFINITIONS -->
    <script>
        const ADMIN_PATH = '<?= $this->Url->build('/', ['fullBase' => true]); ?>';
        const TEMPLATES_PATH = ADMIN_PATH + 'webroot/js/templates/';
        const COLORS = <?= json_encode(Configure::read('UI.colors_bg')); ?>;
        const TEXTCOLOR_MAP = <?= json_encode(Configure::read('UI.colors_ft')); ?>;
        const TYPOGRAPHY = <?= json_encode(Configure::read('UI.typography')); ?>;
    </script>
</head>
<body>
    <div id="container">
        <?= $this->element('main-menu', $menuItems); ?>

        <div id="main-content"  <?= isset($_SESSION['menu_hide']) && $_SESSION['menu_hide'] ? 'class="wide"' : ''?>>
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content'); ?>
            <?= $this->element('spinner'); ?>
        </div><!-- #flow-content -->
    </div><!-- #container -->
    <div class="notification hidden" onclick="this.classList.add('hidden')">
        <span class="close"><i class="fas fa-times"></i></span>
        <div class="nt-content"><?= isset($message)? $message: ''; ?></div>
    </div><!-- .notification -->
</body>
</html>
