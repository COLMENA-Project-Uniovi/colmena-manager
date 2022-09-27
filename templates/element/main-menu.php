<?php

use Cake\Utility\Inflector;
?>
<div class="nav">
    <nav>
        <div class="content content-nav">
            <div class="logo">
                <?= $this->Html->link(
                    $this->Html->image(
                        'logo.svg',
                        ['alt' => 'logo']
                    ),
                    "/",
                    ['escape' => false]
                ); ?>
            </div><!-- .logo -->

            <div class="items">
                <?php
                $actualPlugin = $this->request->getParam('plugin');
                $actualPlugin = $actualPlugin === null ? false : $actualPlugin;
                $is_home = false;

                if (strcmp($this->request->getRequestTarget(), 'pages/home') != 0) {
                    $is_home = true;
                }

                foreach ($menuItems as $section_name => $content_menu) {
                    $items = $content_menu['items'];

                    foreach ($items as $title => $menu_item) {
                        if ($menu_item['link']['controller'] === $this->request->getParam('controller')) {
                            $menu_item['extra']['class'] .= ' current';
                        }

                        $title = $menu_item['icon'] . $title;

                        echo $this->Html->link(
                            $title,
                            $menu_item['link'],
                            $menu_item['extra']
                        );
                    }
                ?>
                <?php
                }
                ?>
            </div><!-- .items -->

            <div class="logout">
                <span class="user" style="font-size: 15px;">
                    <i class="fal fa-user-circle"></i>
                    <?php
                    if (!isset($user)) {
                        header('Location: ' . 'https://' . $_SERVER['HTTP_HOST'] . '/admin');
                        die();
                    }
                    ?>
                </span>
                <?= $this->Html->link(
                    '<i class="fa fa-power-off"></i>',
                    [
                        'controller' => 'AdminUsers',
                        'action' => 'logout',
                        'plugin' => false
                    ],
                    [
                        'escape' => false
                    ]
                ); ?>
            </div><!-- .logout -->
        </div><!-- .content-nav -->
    </nav>
</div><!-- .nav -->