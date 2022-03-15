<?php
use Cake\Utility\Inflector;
?>
<div class="nav">
    <nav <?= isset($_SESSION['menu_hide']) && $_SESSION['menu_hide'] ? 'class="closed"' : ''?>>
        <div class="content-nav">
            <div class="logo">
                <?= $this->Html->link(
                    $this->Html->image(
                        'logo.svg',
                        ['alt' => 'logo']
                    ),
                    "/" ,
                    ['escape' => false]
                ); ?>
                <p class="close"><i class="fa fa-bars"></i></p>
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
                    $is_active = '';
                    foreach ($items as $title => $menu_item) {
                        if (($this->request->getParam('plugin') !== false &&
                            $menu_item['link']['plugin'] === $actualPlugin)) {
                            $is_active = 'is_active';
                            break;
                        }
                    }
                    ?>
                <div class="accordion <?= $is_active ?>">
                    <div class="menu-title header">
                        <span class="icon"><?= $content_menu['extra']['ico']; ?></span>
                        <span class="name"><?= $section_name; ?></span>
                        <span class="arrow"><i class="fas fa-caret-right"></i></span>
                    </div>
                    <div class="content">
                    <?php
                    foreach ($items as $title => $menu_item) {
                        if ($menu_item['link']['controller'] === $this->request->getParam('controller')){
                            $menu_item['extra']['class'] .= ' current';
                        }
                        echo $this->Html->link(
                            $title,
                            $menu_item['link'],
                            $menu_item['extra']
                        );
                    }
                    ?>
                    </div><!-- .content -->
                </div><!-- .accordion -->
                    <?php
                }
                ?>
            </div><!-- .items -->
            <div class="logout">
                <span class="user">
                    <i class="fas fa-user"></i>
                    <?= $user['username']; ?> - <?= $user_role['name'] ?>
                </span>
                <?= $this->Html->link(
                    'Salir&nbsp;&nbsp;&nbsp;<i class="fa fa-power-off"></i>',
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
    <nav class="small">
        <div class="content-nav">
            <div class="open"><i class="fa fa-bars"></i></div>
            <div class="wrapper-items">
                <div class="items">
                    <?php
                    $is_home = false;

                    if (strcmp($this->request->getRequestTarget(), 'pages/home') != 0) {
                        $is_home = true;
                    }
                    // $menuItems declarado en AppController::beforeFilter()
                    foreach ($menuItems as $section_name => $content_menu) {
                        $items = $content_menu['items'];
                        $is_active = '';
                        foreach ($items as $title => $menu_item) {
                            if (($this->request->getParam('plugin') === false &&
                                $menu_item['link']['controller'] === $this->request->getParam('controller')) ||
                                ($this->request->getParam('plugin') !== false &&
                                $menu_item['link']['plugin'] === $this->request->getParam('plugin'))) {
                                $is_active = 'is_active';
                                break;
                            }
                        }
                        ?>
                        <div class="menu-icon <?= $is_active; ?>">
                            <span class="icon" title="<?= $section_name; ?>"><?= $content_menu['extra']['ico'];?></span>
                            <div class="sub-menu">
                                <span class="name"><?= $section_name; ?></span>
                                <?php
                                foreach ($items as $title => $menu_item) {
                                    if ($menu_item['link']['action'] === $this->request->getParam('action') &&
                                        $menu_item['link']['controller'] === $this->request->getParam('controller') &&
                                        $menu_item['link']['plugin'] === $this->request->getParam('plugin')){
                                        $menu_item['extra']['class'] .= ' current';
                                    }
                                    echo $this->Html->link(
                                        $title,
                                        $menu_item['link'],
                                        $menu_item['extra']
                                    );
                                }
                                ?>
                            </div><!-- .sub-menu -->
                        </div><!-- .accordion -->
                        <?php
                    }
                    ?>
                </div><!-- .items -->
            </div><!-- .wrapper-items -->
            <div class="logout">
                <?= $this->Html->link(
                    '<i class="fa fa-power-off"></i>',
                    [
                        'controller' => 'AdminUsers',
                        'action' => 'logout',
                        'plugin' => false
                    ],
                    [
                        'class' => 'menu-item exit',
                        'escape' => false
                    ]
                ); ?>
            </div><!-- .logout -->
        </div><!-- .content-nav -->
    </nav>
</div><!-- .nav -->
