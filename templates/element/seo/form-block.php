<?php
$block_title = 'Posicionamiento SEO';
if (isset($title) && $title != '') {
    $block_title = $title;
}

$index = '';
if (isset($prefix) && $prefix !== '') {
    $index = $prefix . '.';
}
?>
<div class="form-block seo multimedia">
    <h3><?= $block_title; ?></h3>
    <div class="seo-tabs tabs">
        <div class="tabs-header">
        <?php
        foreach ($seo as $seo_type => $seo_config) {
            ?>
            <div class="tab" data-tab="<?= $seo_type; ?>">
                <?= $seo_config['name']; ?>
            </div><!-- .tab -->
            <?php
        }
        ?>
        </div><!-- .tabs-header -->
        <div class="tabs-content">
        <?php
        foreach ($seo as $seo_type => $seo_config) {
            ?>
            <div class="seo-block tab" data-tab="<?= $seo_type; ?>">
                <p class="seo-description"><?= $seo_config['description']; ?></p>
                <?php
                foreach ($seo_config['fields'] as $field_name => $field_config) {
                    $input_name = $index . 'seo.' . $seo_type . '.' . $field_name;
                    $input_config = [];
                    foreach ($field_config as $conf => $value) {
                        if ($conf == 'help' || $conf == 'max') {
                            $input_config['templateVars'][$conf] = $value;
                        } else {
                            $input_config[$conf] = $value;
                        }
                    }

                    switch ($field_config['type']) {
                        case 'textarea':
                            $input_config['rows'] = 3;
                            break;
                        case 'select':
                            $input_config['escape'] = false;

                            if (isset($field_config['empty'])) {
                                $input_config['empty'] = [$field_config['empty']];
                            }
                    }
                    if ($field_config['type'] != 'image' &&
                        $field_config['type'] != 'file' &&
                        $field_config['type'] != 'color') {
                        echo $this->Form->control(
                            $input_name,
                            $input_config
                        );
                    } else if ($field_config['type'] == 'color') {
                        $input_config['type'] = 'text';
                        $input_class = 'js-color-selector';
                        if (!isset($input_config['class'])) {
                            $input_config['class'] = '';
                        }
                        $input_config['class'] .= ' ' . $input_class;
                        ?>
                        <div class="color">
                            <div class="sample" style="background-color: <?= isset($entity['seo'][$seo_type]) ? $entity['seo'][$seo_type][$field_name] : "#000"; ?>"></div><!-- .sample -->
                            <?= $this->Form->control(
                                $input_name,
                                $input_config
                            );?>
                        </div><!-- .color -->
                        <?php
                    } else {
                        $media_config = [
                            'name' => $field_config['label'],
                            'custom' => true
                        ];

                        $resources = [];
                        if (isset($entity['seo'][$seo_type][$field_name]) &&
                            $entity['seo'][$seo_type][$field_name] != '') {
                            $resources = [
                                'path' => $entity['seo'][$seo_type][$field_name]
                            ];
                        }

                        echo $this->element(
                            "Neo/MediaManager.media/" . $field_config['type'] . "-unique-block",
                            [
                                'media_name' => $input_name,
                                'config' => $media_config,
                                'resources' => $resources
                            ]
                        );
                    }
                }
            ?>
            </div><!-- .seo-block.tab -->
            <?php
        }
        ?>
        </div><!-- .tabs-content -->
    </div><!-- .seo-tabs -->
</div><!-- .form-block -->
<?= $this->Html->script(
    'Neo/MediaManager.media-block-functions'
); ?>
<?= $this->element('form/codeeditor-scripts'); ?>
<script type="text/javascript">
    Configure.write(
        'MEDIA_PLUGIN_PATH',
        "<?= $this->Url->build(['controller' => '', 'action' => 'index', 'plugin' => 'Neo/MediaManager'], ['fullBase' => true]); ?>"
    );

    loadMediaBlockFunctions();
</script>
