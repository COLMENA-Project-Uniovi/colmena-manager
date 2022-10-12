<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

class RolesComponent extends Component
{
    public $components = ['Auth'];

    /**
     * Check if the user role is authorized to do some actions
     *
     * @param AdminUserRole $user_role
     * @return boolean if the user is authorized or not
     */
    public function checkAuthorized($role)
    {
        $rolable_entities = Configure::read('Roles.rolable_entities');
        $special_method_entity_roles = Configure::read('Roles.special_method_entity_roles');
        $special_method_general_roles = Configure::read('Roles.special_method_general_roles');

        $current_entity = $this->request->getParam('plugin') != null ?
            $this->request->getParam('plugin') . '.' . Inflector::camelize($this->request->getParam('controller')) :
            Inflector::camelize($this->request->getParam('controller'));

        //if specific entity role exists, check if action is allowed (load from Model)
        if (isset($rolable_entities[$current_entity])) {
            $roles_permissions_table = TableRegistry::getTableLocator()->get('AdminUserRolesPermissions');
            $entity_roles = $roles_permissions_table->find('all', [
                'conditions' => [
                    'role_id' => $role['id'],
                    'model' => $current_entity
                ]
            ])->toArray();

            if (!empty($entity_roles)) {
                $allowed_actions = $entity_roles[0]['actions'];

                if (sizeof(explode(',', $allowed_actions)) == 4) {
                    //if user has full actions (index,add,edit,delete) for entity
                    /**
                     * This is for special actions (like user_roles in AdminUsersController),
                     * if user has full control over entity it must be able to access
                     * all of the entity's actions.
                     */
                    return true;
                } elseif (in_array($this->request->getParam('action'), explode(',', $allowed_actions))) {
                    //if user hasn't full roles, check entity specific actions
                    return true;
                } else {
                    //check special entity roles and general roles
                    $entity_special_methods =
                        isset($special_method_entity_roles[$allowed_actions][$current_entity]) ?
                        $special_method_entity_roles[$allowed_actions][$current_entity] :
                        [];
                    $general_special_methods =
                        isset($special_method_general_roles[$allowed_actions]) ?
                        $special_method_general_roles[$allowed_actions] :
                        [];
                    if (in_array($this->request->getParam('action'), $entity_special_methods)) {
                        return true;
                    } elseif (in_array($this->request->getParam('action'), $general_special_methods)) {
                        return true;
                    }
                }
            }
        }
    }

    /**
     * Method to calculate the menuItems to display for the current user
     *
     * @param  array $menuItems all menuItems
     * @param  User $user       current user
     *
     * @return menuItems to display
     */
    public function userMenuItems()
    {
        $menuItems = Configure::read('UI.menuItems');
        $user = $this->Auth->user();

        $roles_table = TableRegistry::getTableLocator()->get('AdminUserRoles');
        $role =  $roles_table->getRoleFromUser($user);

        if (isset($this->request) && $this->request->getParam('action') != 'login' && !is_null($user) && !$role['is_admin']) {
            //only check when action is not login and user is not admin
            foreach ($menuItems as $section_name => $content_menu) {
                $items = $content_menu['items'];
                foreach ($items as $title => $menu_item) {
                    if (!$this->isItemAvailable($menu_item['link'])) {
                        //unset menu_item due to lack of permissions
                        unset($items[$title]);
                    }
                }

                if (!empty($items)) {
                    $menuItems[$section_name]['items'] = $items;
                } else {
                    unset($menuItems[$section_name]);
                }
            }
        }

        return $menuItems;
    }

    /**
     * Method to calculate the home_blocks to display for the current user
     *
     * @param  $home_blocks all home_blocks
     * @param  $user        current user
     *
     * @return home_blocks to display
     */
    public function userHomeBlocks()
    {
        $home_blocks = Configure::read('UI.home_blocks');
        $user = $this->Auth->user();

        $roles_table = TableRegistry::getTableLocator()->get('AdminUserRoles');
        $role =  $roles_table->getRoleFromUser($user);

        if (isset($this->request) && $this->request->getParam('action') == 'display' && !$role['is_admin']) {
            //only check when action is display and user is not admin
            foreach ($home_blocks as $block_name => $content_block) {
                if ($this->isBlockAvailable($content_block['className'])) {
                    if (isset($content_block['query']['button'])) {
                        //if button for each row is present, check permissions
                        if (!$this->isItemAvailable($content_block['query']['button']['link'])) {
                            unset($home_blocks[$block_name]['query']['button']);
                        }
                    }
                    //if block available, check button permissions (main_buttons)
                    if (!empty($content_block['main_buttons'])) {
                        foreach ($content_block['main_buttons'] as $action_name => $action_link) {
                            if (!$this->isItemAvailable($action_link['link'])) {
                                //unset menu_item due to lack of permissions
                                unset($content_block['main_buttons'][$action_name]);
                            }
                        }
                        $home_blocks[$block_name]['main_buttons'] = $content_block['main_buttons'];
                    }
                    //if block available, check button permissions (bottom_buttons)
                    if (!empty($content_block['bottom_buttons'])) {
                        foreach ($content_block['bottom_buttons'] as $action_name => $action_link) {
                            if (!$this->isItemAvailable($action_link['link'])) {
                                //unset menu_item due to lack of permissions
                                unset($content_block['bottom_buttons'][$action_name]);
                            }
                        }
                        $home_blocks[$block_name]['bottom_buttons'] = $content_block['bottom_buttons'];
                    }
                } else {
                    //block unavailable, remove completely
                    unset($home_blocks[$block_name]);
                }
            }
        }

        return $home_blocks;
    }

    /**
     * Check if home_block should be shown to the user. If the user has index
     * action available, show block. Block buttons will be checked in separate
     * method (isItemAvailable).
     *
     * @param  String  $class_name Entity name to check permissions
     *
     * @return boolean             result of the access test
     */
    private function isBlockAvailable($class_name)
    {
        $user = $this->Auth->user();

        $rolable_entities = Configure::read('Roles.rolable_entities');

        $role_id = $user['id'];
        $current_entity = $class_name;

        //if specific entity role exists, check if action is allowed (load from Model)
        if (isset($rolable_entities[$current_entity])) {
            $roles_table = TableRegistry::getTableLocator()->get('AdminUserRolesPermissions');
            $entity_roles = $roles_table->find('all', [
                'conditions' => [
                    'role_id' => $role_id,
                    'model' => $current_entity
                ]
            ])->toArray();

            if (!empty($entity_roles)) {
                $allowed_actions = $entity_roles[0]['actions'];
                //if user hasn't full roles, check entity specific actions
                if (in_array('index', explode(',', $allowed_actions))) {
                    return true;
                }
            }
        }

        // If user does not have role actions for entity or entity is not rollable, deny access.
        return false;
    }

    /**
     * Method to calculate if a given action in one of the entities
     * available is accessible for the current loged in user.
     *
     * @param  Array  $item menu item with the details (entity, action, plugin)
     *
     * @return boolean       result of the access test
     */
    private function isItemAvailable($item)
    {
        $user = $this->Auth->user();

        $rolable_entities = Configure::read('Roles.rolable_entities');
        $special_method_entity_roles = Configure::read('Roles.special_method_entity_roles');
        $special_method_general_roles = Configure::read('Roles.special_method_general_roles');

        if (isset($user)) {
            $role_id = $user['role_id'];

            $current_entity = (isset($item['plugin']) && ($item['plugin'] != false)) ?
                $item['plugin'] . '.' . Inflector::camelize($item['controller']) : Inflector::camelize($item['controller']);

            //if specific entity role exists, check if action is allowed (load from Model)
            if (isset($rolable_entities[$current_entity])) {
                $roles_table = TableRegistry::getTableLocator()->get('AdminUserRolesPermissions');
                $entity_roles = $roles_table->find('all', [
                    'conditions' => [
                        'role_id' => $role_id,
                        'model' => $current_entity
                    ]
                ])->toArray();

                if (!empty($entity_roles)) {
                    $allowed_actions = $entity_roles[0]['actions'];

                    if (sizeof(explode(',', $allowed_actions)) == 4) {
                        //if user has full actions (index,add,edit,delete) for entity
                        /**
                         * This is for special actions (like user_roles in AdminUsersController),
                         * if user has full control over entity it must be able to access
                         * all of the entity's actions.
                         */
                        return true;
                    } elseif (in_array($item['action'], explode(',', $allowed_actions))) {
                        //if user hasn't full roles, check entity specific actions
                        return true;
                    } else {
                        //check special entity roles and general roles
                        $entity_special_methods =
                            isset($special_method_entity_roles[$allowed_actions][$current_entity]) ?
                            $special_method_entity_roles[$allowed_actions][$current_entity] :
                            [];
                        $general_special_methods =
                            isset($special_method_general_roles[$allowed_actions]) ?
                            $special_method_general_roles[$allowed_actions] :
                            [];
                        if (in_array($item['action'], $entity_special_methods)) {
                            return true;
                        } elseif (in_array($item['action'], $general_special_methods)) {
                            return true;
                        }
                    }
                }
            }
        }

        // If user does not have role actions for entity or entity is not rollable, deny access.
        return false;
    }

    /**
     * Compose user options for admin views (header actions, table actions and possible
     * tabs)
     *
     * @param  array $general_options all options available
     *
     * @return array                  user options based on permissions
     */
    public function composeUserOptions($general_options)
    {
        $user = $this->Auth->user();
        $roles_table = TableRegistry::getTableLocator()->get('AdminUserRoles');
        $role =  $roles_table->getRoleFromUser($user);

        if (isset($role) && !$role['is_admin']) {
            if (isset($general_options)) {
                foreach ($general_options as $key => $value) {
                    if (!$this->isItemAvailable($value['url'])) {
                        unset($general_options[$key]);
                    }
                }
            }
        }

        //return the available options for the user
        return $general_options;
    }

    /**
     * Compose user tabs for admin views
     *
     * @param  array $general_tabs all tabs available
     *
     * @return array                  user tabs based on permissions
     */
    public function composeUserTabs($general_tabs)
    {
        $user = $this->Auth->user();
        $roles_table = TableRegistry::getTableLocator()->get('AdminUserRoles');
        $role =  $roles_table->getRoleFromUser($user);

        if (isset($role) && !$role['is_admin']) {
            foreach ($general_tabs as $key => $value) {
                if (!$this->isItemAvailable($value['url'])) {
                    unset($general_tabs[$key]);
                }
            }
        }

        //return the available tabs for the user
        return $general_tabs;
    }
}
