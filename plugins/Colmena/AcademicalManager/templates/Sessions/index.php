<?php

use Cake\Utility\Inflector;

$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add('Asignaturas', [
  'controller' => 'Subjects',
  'action' => 'index'
]);
$this->Breadcrumbs->add($subject->name, [
  'controller' => 'Subjects',
  'action' => 'edit', $subject->id
]);

$this->Breadcrumbs->add('Sesiones', [
  'controller' => 'Sessions',
  'action' => 'index',
  $subject->id
]);

array_push($header_actions['Añadir sesión']['url'], $subject->id);

$header = [
  'title' => ucfirst($entityNamePlural),
  'breadcrumbs' => true,
  'header' => [
    'actions' => $header_actions,
    'search_form' => []
  ]
];
?>

<?= $this->element("header", $header); ?>

<div class="content p-4">
  <div class="results">
    <?php
    if (count($entities) !== 0 && !empty($entities)) {
    ?>
      <table class="table">
        <thead class="thead">
          <tr>
            <th class="grow">
              Nombre
            </th><!-- .th -->
            <th class="grow">
              Lenguaje de programación
            </th><!-- .th -->
            <?php
            if (!empty($tableButtons)) {
            ?>
              <th class="actions short">
                Operaciones
              </th><!-- .th -->
            <?php
            }
            ?>
          </tr><!-- .tr -->
        </thead><!-- .thead -->
        <tbody class="elements">
          <?php
          foreach ($entities as $entity) {
          ?>
            <tr>
              <td class="element grow">
                <p><?= $entity->name ?></p>
              </td><!-- .td -->
              <td class="element grow">
                <p> <?= $entity->language_id != 0 ? $entity->language->name : ''; ?></p>
              </td><!-- .td -->
              <?php
              if (!empty($tableButtons)) {
              ?>
                <td class="actions">
                  <div class="td-content" style="width: 50%;">
                    <?php
                    foreach ($tableButtons as $key => $value) {
                      array_push($value['url'], $entity->id);
                      array_push($value['url'], $subject->id);

                      if ($value['url']['action'] != 'delete') {
                        echo $this->Html->link(
                          $value['icon'],
                          $value['url'],
                          $value['options']
                        );
                      } else {
                        echo $this->Form->postLink(
                          $value['icon'],
                          $value['url'],
                          $value['options']
                        );
                      }
                    }
                    ?>
                  </div><!-- .td-content -->
                </td><!-- .td -->
              <?php
              }
              ?>
            </tr><!-- .tr -->
          <?php
          }
          ?>
        </tbody><!-- .tbody -->
      </table><!-- .table -->
      <?= $this->element('paginator'); ?>
    <?php
    } else {
    ?>
      <p class="no-results">No existen resultados para la búsqueda realizada</p>
    <?php
    }
    ?>
  </div><!-- .results -->
</div><!-- .content -->