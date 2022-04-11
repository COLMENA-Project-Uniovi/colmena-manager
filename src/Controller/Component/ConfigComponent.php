<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;

class ConfigComponent extends Component
{
    /**
     * Initialize all the configuration needed for the application.
     */
    public function initialize(array $config): void
    {
        $this->initMenu();
        $this->initHomeBlocks();
        $this->initRoles();
        $this->initApi();
        $this->initTypography();
    }

    /**
     * Configure menu items.
     */
    private function initMenu()
    {
        $menuItems = [];
        foreach (Configure::read() as $key => $config) {
            if (isset($config['menuItems'])) {
                $menuItems = array_merge($menuItems, $config['menuItems']);
            }
        }

        uasort($menuItems, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        Configure::write('UI.menuItems', $menuItems);
    }

    /**
     * Configure home blocks.
     */
    private function initHomeBlocks()
    {
        $home_blocks = [];
        foreach (Configure::read() as $key => $config) {
            if (isset($config['home_blocks'])) {
                $home_blocks = array_merge($home_blocks, $config['home_blocks']);
            }
        }
        
        uasort($home_blocks, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        Configure::write('UI.home_blocks', $home_blocks);
    }

    /**
     * Configure roles.
     */
    private function initRoles()
    {
        /**
         * Set the entities affected by user roles.
         */
        $rolable_entities = [];
        foreach (Configure::read() as $key => $config) {
            if (isset($config['rolable_entities'])) {
                $rolable_entities = array_merge($rolable_entities, $config['rolable_entities']);
            }
        }
        
        Configure::write('Roles.rolable_entities', $rolable_entities);

        /*
         * Set the possible permissions that can affect the entities
         */
        Configure::write(
            'Roles.possible_permissions',
            [
                'index' => 'Listado',
                'index,edit' => 'Listado y edición',
                'index,add,edit' => 'Listado, creación y edición',
                'index,add,edit,delete' => 'Completo',
            ]
        );

        /*
         * Special method general roles (for all entities)
         */
        Configure::write(
            'Roles.special_method_general_roles',
            [
                'index' => [
                    'move',
                ],
                'index,edit' => [
                    'changeBoolean',
                    'move',
                ],
                'index,add,edit' => [
                    'changeBoolean',
                    'move',
                ],
                'index,add,edit,delete' => [
                    'changeBoolean',
                    'move',
                ],
            ]
        );

        /**
         * Set the special roles for entities methods.
         */
        $special_method_entity_roles = [];
        foreach (Configure::read() as $key => $config) {
            if (isset($config['special_method_entity_roles'])) {
                $special_method_entity_roles = array_merge_recursive($special_method_entity_roles, $config['special_method_entity_roles']);
            }
        }

        Configure::write('Roles.special_method_entity_roles', $special_method_entity_roles);
    }

    /**
     * Configure API.
     */
    private function initApi()
    {
        /**
         * Set the available searchable entities from the front to use in UrlController
         * Every table declared here MUST implement the App\Model\Inheritance\Searchable.
         */
        $searchable_entities = [];
        foreach (Configure::read() as $key => $config) {
            if (isset($config['searchable_entities'])) {
                $searchable_entities = array_merge($searchable_entities, $config['searchable_entities']);
            }
        }

        uasort($searchable_entities, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        Configure::write('API.searchable_entities', array_keys($searchable_entities));

        /*
         * API encryption configuration
         */
        Configure::write('API.key', hash('sha256', 'N30s1t3K3y_API'));
        Configure::write('API.method', 'aes-256-cbc');

        /**
         * API enabled entities.
         */
        $api_entities = [];
        foreach (Configure::read() as $key => $config) {
            if (isset($config['api_entities'])) {
                $api_entities = array_merge($api_entities, $config['api_entities']);
            }
        }

        uasort($api_entities, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        Configure::write('API.entities', array_keys($api_entities));

        /*
         * API enabled versions
         */
        Configure::write('API.versions', [
            '1.0',
        ]);
    }

    /**
     * Configure typography.
     */
    private function initTypography()
    {
        $json = new File(WWW_ROOT.'config/typography.json', true, 0777);

        Configure::write('UI.typography', json_decode($json->read(), true));
    }
}
