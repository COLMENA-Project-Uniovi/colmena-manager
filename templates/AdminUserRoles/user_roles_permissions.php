<?php
use Cake\Utility\Inflector;
$this->Breadcrumbs->add('Inicio', '/');
$this->Breadcrumbs->add('Roles de usuario', [
    'controller' => 'AdminUserRoles',
    'action' => 'index'
]);
$this->Breadcrumbs->add('Editar rol de usuario "' . $currentRole['name'] . '"', [
    'controller' => 'AdminUserRoles',
    'action' => 'edit',
    $currentRole['id']
]);
$this->Breadcrumbs->add('Permisos', [
    'controller' => $this->request->getParam('controller'),
    'action' => 'userRolesPermissions',
    $currentRole['id']
]);
$header = [
    'title' => 'Permisos del rol de usuario "' . $currentRole['name'] . '"',
    'breadcrumbs' => true,
    'tabs' => $tabActions
];
?>

<?= $this->element("header", $header); ?>
<div class="content px-4">
<?php
if ($currentRole->is_admin) {
?>
    <p class="no-results">El usuario administrador tiene permisos para ejecutar todas las acciones del CMS.</p>
<?php
} else {
?>
<?= $this->Form->create(
    $currentRole,
    [
        'class' => 'admin-form',
        'type' => 'file'
    ]
); ?>
    <div class="results">
        <table>
            <thead class="sticky">
                <tr>
                    <th>
                        Entidad
                    </th>
                <?php
                    foreach ($possiblePermissions as $permission) {
                ?>
                    <th class="sortable">
                        <?= $permission; ?>
                    </th>
                <?php
                    }
                ?>
                </tr>
            </thead>
            <tbody>
        <?php
            foreach ($entities as $entity => $config) {
        ?>
                <tr>
                    <td class="element">
                        <p><?= $config['name']; ?></p>
                    </td>
            <?php
                foreach ($possiblePermissions as $permission_key => $permission) {
                    $permission_checked = '';
                    if($permission_key === $config['actions']) {
                        $permission_checked = 'checked';
                    }
            ?>
                    <td class="element center">
                        <div class="td-content user-perms">
                            <input type="radio" name="<?= $entity; ?>" value="<?= $permission_key; ?>" class="radio" <?= $permission_checked; ?>/>
                            <span class="checkmark"></span>
                        </div>
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
    </div><!-- .results -->
    <?= $this->element("form/save-block"); ?>
<?= $this->Form->end(); ?>
<?php
}
?>
</div><!-- .content -->
