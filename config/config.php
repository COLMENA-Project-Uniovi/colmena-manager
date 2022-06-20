<?php

use Cake\Core\Configure;

Configure::write('Project.name', 'Colmena Project');
Configure::write('Cookie.name', 'COLMENA');

$serverName = $_SERVER['SERVER_NAME'];
$arrayServerName = explode('.', $_SERVER['SERVER_NAME']);

if (in_array('beta', $arrayServerName)) {
    $path = '';
    $adminFolder = 'admin';
    $frontFolder = '';

    //Beta Config
    $user = 'colmena_admin';
    $pass = '9W$ecd709';
    $database = 'colmena_bd';
    $host = '94.127.187.103';

    $debug = true;
} else if (in_array('test', $arrayServerName)) {
    $path = '';
    $adminFolder = 'admin';
    $frontFolder = '';

    //Test Config
    $user = '';
    $pass = '';
    $database = '';
    $host = '149.62.172.121';

    $debug = false;
} else {
    $path = '';
    $adminFolder = '';
    $frontFolder = '';

    //Production Config
    $user = '';
    $pass = '';
    $database = '';
    $host = '';

    $debug = false;
}

if (!empty($frontFolder)) {
    $frontFolder = $frontFolder . '/';
}
if (!empty($adminFolder)) {
    $adminFolder = $adminFolder . '/';
}

if (empty($path)) {
    $adminPath = $adminFolder;
    $webPath = $frontFolder;
} else {
    $adminPath = $path . '/' . $adminFolder;
    $webPath = $path . '/' . $frontFolder;
}

Configure::write('Config.domain', $serverName);
Configure::write('Config.admin_path', $adminPath);
Configure::write('Config.web_path', $webPath);
Configure::write('Config.protocol', 'https');
Configure::write('Config.sitemap_dir', '../../' . $frontFolder . 'sitemap.xml');
Configure::write('Config.robots_dir', '../../' . $frontFolder . 'robots.txt');
Configure::write('Config.htaccess_dir', '../../' . $frontFolder . '.htaccess');
Configure::write('Config.htaccess_backup', '../../' . $frontFolder . 'backup/');
Configure::write('Config.resources_dir', '../../' . $frontFolder);
