<header class="card">
    <?php

    use Cake\Core\Configure;
    use Cake\I18n\I18n;

    if ((isset($breadcrumbs) && $breadcrumbs === true) || isset($languages)) {
    ?>
        <div class="top-header">
            <div class="left-header">
                <?= $this->Breadcrumbs->render(
                    ['class' => 'breadcrumbs'],
                    ['separator' => ' &raquo; ']
                ); ?>
            </div>

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
            <?php
            if (isset($languages)) {
            ?>
                <div class="languages">
                    <?php
                    if (count(Configure::read('I18N.locales')) > 1) {
                        foreach (Configure::read('I18N.locales') as $code => $name) {
                            $current = "";
                            if ($code == I18n::getLocale()) {
                                $current = "current";
                            }
                            $link = $languages;
                            array_push($link, $code);
                            echo $this->Html->link($name, $link, ['class' => 'link ' . $current]);
                        }
                    }
                    ?>
                </div><!-- .languages -->
            <?php
            }
            ?>
        </div><!-- .top-header -->
    <?php
    }
    ?>

    <div class="mid-header row">
        <h2 class="col"><?= $title; ?></h2>
        <?php
        if (isset($header)) {
            if (isset($header['actions']) && !empty($header['actions'])) {
        ?>
                <div class="actions col">
                    <?php
                    if (isset($header['title']) && !empty($header['title'])) {
                    ?>
                        <h3><?= $header['title'] ?></h3>
                    <?php
                    }
                    foreach ($header['actions'] as $action_name => $config) {
                        if (!isset($config['options'])) {
                            $config['options'] = [];
                        }
                        if (isset($config['options']['class'])) {
                            $config['options']['class'] .= ' button';
                        } else {
                            $config['options']['class'] = 'button';
                        }
                        echo $this->Html->link(
                            $action_name,
                            $config['url'],
                            $config['options']
                        );
                    }
                    ?>
                </div><!-- .actions -->
            <?php
            }
            if (isset($header['buttons']) && !empty($header['buttons'])) {
            ?>
                <div class="actions col">
                    <?php
                    foreach ($header['buttons'] as $action_name => $config) {
                        $class = isset($config['class']) ? $config['class'] : '';
                    ?>
                        <span class="button <?= $class; ?>" data-action="<?= $config['action']; ?>"><?= $action_name; ?></span>
                    <?php
                    }
                    ?>
                </div><!-- .actions -->
            <?php
            }
            if (isset($header['invoice']) && !empty($header['invoice'])) {
            ?>
                <div class="actions">
                    <?php
                    foreach ($header['invoice'] as $action_name => $config) {
                        $class = isset($config['class']) ? $config['class'] : '';
                    ?>
                        <?= $this->Form->create(
                            null,
                            [
                                'class' => 'button form-invoice'
                            ]
                        ); ?>
                        <?= $this->Form->control(
                            'file',
                            [
                                'label' => 'Factura',
                                'type' => 'file'
                            ]
                        ); ?>
                        <?= $this->Form->control(
                            'date',
                            [
                                'type' => 'hidden',
                                'value' => substr(str_replace('/', '', $config['date']), 0, 8),
                            ]
                        ); ?>
                        <?= $this->Form->control(
                            'ref',
                            [
                                'type' => 'hidden',
                                'value' => $config['ref'],
                            ]
                        ); ?>
                        <?= $this->Form->control(
                            'username',
                            [
                                'type' => 'hidden',
                                'value' => $config['username'],
                            ]
                        ); ?>
                        <?= $this->Form->end(); ?>
                    <?php
                    }
                    ?>
                </div><!-- .actions -->
            <?php
            }
            if (isset($header['dropdown']) && !empty($header['dropdown'])) {
                $layout = isset($header['dropdown']['layout']) ? $header['dropdown']['layout'] : 'four';
            ?>
                <div class="dropdown">
                    <span class="button"><?= $header['dropdown']['name']; ?> <span class="down"><i class="fas fa-angle-right fa-2x"></i></span></span>
                    <div class="options <?= $layout; ?>">
                        <?php
                        foreach ($header['dropdown']['actions'] as $action_name => $config) {
                        ?>
                            <a class="action" href="<?= $this->Url->build($config['url']); ?>">
                                <?php
                                if (isset($config['image'])) {
                                    echo $this->Html->image(
                                        $config['image']
                                    );
                                    unset($config['image']);
                                }
                                ?>
                                <span><?= $action_name; ?></span>
                            </a>
                        <?php
                        }
                        ?>
                    </div><!-- .options -->
                </div><!-- .dropdown -->
            <?php
            }
            if (isset($header['search_form'])) {
                $search_form = $header['search_form'];
            ?>
                <div class="search col <?= isset($header['select_filter']) && $header['select_filter'] ? "long" : "" ?>">
                    <?= $this->Form->create(
                        null,
                        [
                            'url' => isset($header['search_form']['url']) ? $header['search_form']['url'] : ['action' => 'index'],
                            'class' => 'search-form'
                        ]
                    ); ?>
                    <?= $this->Form->control(
                        'keyword',
                        [
                            'label' => false,
                            'type' => 'text',
                            'value' => $keyword
                        ]
                    );
                    
                    if (isset($header['select_filter']) && $header['select_filter']) {
                        echo $this->Form->select(
                            'filter',
                            $filter_options,
                            ['empty' => $header['select_filter']['empty']]
                        );
                    }

                    ?>
                    <?= $this->Form->button(
                        '<i class="fa fa-search" aria-hidden="true"></i>',
                        [
                            'class' => 'button',
                            'escapeTitle' => false
                        ]
                    ); ?>
                    <?= $this->Form->end(); ?>
                </div><!-- .search -->
            <?php
            }
            if (isset($header['ajax_search'])) {
                $action = $header['ajax_search']['action'];
            ?>
                <div class="search-wrapper">
                    <input class="search-input" type="text" placeholder="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="feather feather-search" viewBox="0 0 24 24">
                        <defs></defs>
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                </div>
            <?php
            }

            if (isset($header['close'])) {
            ?>
                <div class="close-button">
                    <span class="button close">Cerrar <i class="fa fa-times"></i></span>
                </div><!-- .close-button -->
        <?php
            }
        }
        ?>
    </div><!-- .mid-header -->

    <?php
    if (isset($tabs) && !empty($tabs)) {
    ?>
        <div class="header-tabs">
            <div class="content-tabs">
                <?php
                foreach ($tabs as $tab_name => $config) {
                    echo $this->Html->link(
                        $tab_name,
                        $config['url'],
                        [
                            'class' => 'tab ' . $config['current']
                        ]
                    );
                }
                ?>
            </div><!-- .content-tabs -->
        </div><!-- .header-tabs -->
    <?php
    }

    if (isset($keyword) && $keyword != "") {
    ?>
        <p class="search-results">Resultados de la búsqueda: <strong><?= $keyword; ?></strong></p>
    <?php
    }
    ?>
</header>