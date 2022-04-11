<?php
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

$posts = TableRegistry::getTableLocator()->get($className);

$entities = $posts->find($query['find']);
if (isset($query['where'])) {
    $entities->where($query['where']);
}
if (isset($query['order'])) {
    $entities->order($query['order']);
}
if (isset($query['contain'])) {
    $entities->contain($query['contain']);
}
if (isset($query['limit'])) {
    $entities->limit($query['limit']);
}

$entities_count = $posts->find($count['find']);
if (isset($count['where'])) {
    $entities_count->where($count['where']);
}
if (isset($count['order'])) {
    $entities_count->order($count['order']);
}
if (isset($count['contain'])) {
    $entities_count->contain($count['contain']);
}

$entities_count = $entities_count->count();
?>
<div class="block">
    <div class="header">
        <div class="title">
            <div class="number"><?= $entities_count; ?></div>
            <div class="text"><?= $title; ?></div>
        </div><!-- .title -->
        <div class="main-buttons">
        <?php
            foreach ($main_buttons as $title => $config) {
                echo $this->Html->link(
                    $title,
                    $config['link'],
                    [
                        'class' => 'button white'
                    ]
                );
            }
        ?>
        </div><!-- .main-buttons -->
    </div><!-- .header -->
    <div class="results">
        <table class="tree">
            <tbody>
        <?php
            foreach ($entities as $entity) {
        ?>
                <tr>
                    <td class="date">
                        <p><?= $entity->published_date; ?></p>
                        <p><?= $entity->published_time->i18nFormat(Configure::read('I18N.formats.default.time')); ?></p>
                    </td>
                    <td class="element">
                        <p class="level-<?= $entity->level; ?>"><?= $entity->title; ?></p>
                    </td>
                    <td>
                        <?= $entity->category->name; ?>
                    </td>
                    <td class="actions">
                    <?php
                        $link = $query['button']['link'];
                        array_push($link, $entity->id);
                        echo $this->Html->link(
                            $query['button']['name'],
                            $link,
                            [
                                'class' => 'button fullwidth'
                            ]
                        );
                    ?>
                    </td>
                </tr>
        <?php
            }
        ?>
            </tbody>
        </table>
    </div><!-- .results -->
    <div class="buttons">
    <?php
        foreach ($bottom_buttons as $title => $config) {
            echo $this->Html->link(
                $title,
                $config['link'],
                [
                    'class' => 'button'
                ]
            );
        }
    ?>
    </div><!-- .buttons -->
</div><!-- .block -->
