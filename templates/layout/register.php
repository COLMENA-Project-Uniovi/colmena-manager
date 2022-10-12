<?php
// Some usefull classes needed in default view
use Cake\Cache\Cache;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
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

        $this->Html->css('style', ['block' => 'css']);

        echo $this->fetch('css');
    ?>
</head>
<body>
    <div id="container">
        <?= $this->fetch('content'); ?>
    </div><!-- #container -->
</body>
</html>
