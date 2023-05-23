<?php

use Cake\ORM\TableRegistry;

$sections = TableRegistry::getTableLocator()->get($className);

$entities = $sections->find($query['find']);
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

$entities_count = $sections->find($count['find']);
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
              <p><?= $entity->created; ?></p>
            </td>
            <td class="element">
              <p><?= $entity->name; ?></p>
            </td>
            <td class="actions">
              <?php
              if (isset($query['button']['link'])) {
                $link = $query['button']['link'];
                array_push($link, $entity->id);
                echo $this->Html->link(
                  $query['button']['name'],
                  $link,
                  [
                    'class' => 'button fullwidth'
                  ]
                );
              }
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