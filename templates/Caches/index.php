<?php
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add(ucfirst($entity_name), [
    'controller' => $this->request->getParam('controller'),
    'action' => 'index'
]);
$header = [
    'title' => ucfirst($entity_name),
    'breadcrumbs' => true,
    'tabs' => $tab_actions,
    'header' => [
        'actions' => $header_actions,
        'search_form' => []
    ]
];
?>
<?= $this->element('header', $header); ?>

<div class="content">
    <div class="results">
        <?php
            if($config->value == 'true') {
        ?>
            <?php
                if (!empty($folders)) {
            ?>
                <table>
                    <thead>
                        <tr>
                            <th>
                                Modelo
                            </th>
                            <th>
                                Nombre
                            </th>
                            <th>
                                Carpeta
                            </th>
                            <th>
                                Idioma
                            </th>
                            <?php
                                if (!empty($table_buttons)) {
                            ?>
                            <th class="actions short">
                                Operaciones
                            </th>
                            <?php
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach ($folders as $entity) {
                ?>
                        <tr>
                            <td class="element">
                                <p><?= $entity->model; ?></p>
                            </td>
                            <td class="element">
                                <p><?= $entity->name; ?></p>
                            </td>
                            <td class="element">
                                <p>/<?= $entity->url; ?></p>
                            </td>
                            <td class="element">
                                <p><?= $entity->locale; ?></p>
                            </td>
                            <?php
                                if (!empty($table_buttons)) {
                            ?>
                                <td class="actions">
                                <?php
                                    foreach ($table_buttons as $key => $value) {
                                        $value['url']['?'] = [];
                                        $value['url']['?']['model'] = $entity->model;
                                        $value['url']['?']['url'] = $entity->url;
                                        $value['url']['?']['locale'] = $entity->locale;
                                        echo $this->Html->link(
                                            $key,
                                            $value['url'],
                                            $value['options']
                                        );
                                    }
                                ?>
                                </td>
                            <?php
                                }
                            ?>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>
            <?php
                }
            ?>
        <?php
            } else {
        ?>  
            <p class="no-results">La cache está desactivada</p>
        <?php
            }
        ?>
    </div>
</div>
