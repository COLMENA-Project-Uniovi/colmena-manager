<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 *
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use App\Utility\CacheUtility;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\I18n\I18n;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Application Controller.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @see http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     * @throws Exception
     */
    protected $tableButtons = [];

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        // Add Paginator component
        $this->loadComponent('Paginator');
        // Add Auth component for login
        $this->loadComponent('Auth', [
            'authorize' => 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password',
                    ],
                    'userModel' => 'AdminUsers',
                ],
            ],
            'loginAction' => [
                'controller' => 'AdminUsers',
                'action' => 'login',
                'plugin' => false,
            ],
            'authError' => false,
            'unauthorizedRedirect' => $this->referer(),
        ]);

        // Load the roles component to provide methods to check the privileges
        // of the buttons and actions of the different controllers
        $this->loadComponent('Roles');
        // Load the config component to initialize methods to configure
        // different variables used further in the application
        $this->loadComponent('Config');

        // Allow the display action so our pages controller
        // continues to work.
        $this->Auth->allow(['index', 'login']);

        // Set the API Key to use TinyPNG
        \Tinify\setKey(Configure::read("tinypng.api_key"));

        $this->encryption_key = Configure::read('API.key');
        $this->encryption_method = Configure::read('API.method');
    }

    /**
     * Before filter.
     *
     * @param Event $event the beforeFilter event
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $controller = $this->request->getParam('controller');

        if ($this->request->is('json')) {
            // If is an API call
            $supported_entities = Configure::read('API.entities');
            $plugin = $this->request->getParam('plugin');
            $entity = ($plugin != '' ? $plugin . '.' : '') . $controller;
            // If the controller is a REST controller
            if (in_array($entity, $supported_entities)) {
                // If a version is indicated
                if ($this->request->getParam('version') !== null) {
                    $supported_versions = Configure::read('API.versions');
                    // If the version is supported
                    if (in_array($this->request->getParam('version'), $supported_versions)) {
                        $this->response
                            ->withHeader('Access-Control-Allow-Origin', '*')
                            ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
                            ->withHeader('Access-Control-Allow-Methods', '*');
                        $this->Auth->allow(['index', 'view']);
                    } else {
                        throw new BadRequestException('Versión de API no soportada');
                    }
                } else {
                    throw new BadRequestException('No has especificado una versión de la API');
                }
            } else {
                throw new BadRequestException('Controlador no soportado');
            }
        } else if ($controller != 'Caches') {
            // Every time someone changes something in the CMS, clear the cache.
            // Thanks to this, when a new request is triggered, the cache is renewed.
            (new CacheUtility)->clear();
        }
    }

    /**
     * Before render callback.
     *
     * @param Event $event the beforeRender event
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        $viewVars = $this->viewBuilder()->getVars();

        if (
            !array_key_exists('_serialize', $viewVars) &&
            in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', []);
        } elseif (array_key_exists('_serialize', $viewVars)) {
            // Format an standard JSON response
            $data = [];
            if (!is_array($viewVars['_serialize'])) {
                $data = $viewVars[$viewVars['_serialize']];
            } else {
                foreach ($viewVars['_serialize'] as $key) {
                    $data[$key] = $viewVars[$key];
                }
            }
            $this->set('message', 'OK');
            $this->set('url', $this->request->getRequestTarget());
            $this->set('code', '200');
            $this->set('data', $data);
            $this->set('_serialize', ['message', 'url', 'code', 'data']);
        }

        $user = $this->Auth->user();
        // Set the user info to use in the views if needed
        $this->set('user', $user);

        $rolesTable = TableRegistry::getTableLocator()->get('AdminUserRoles');
        $user_role =  $rolesTable->getRoleFromUser($user);
        $this->set('user_role', $user_role);

        // MENU ITEMS: load based on user permissions
        $this->set('menuItems', $this->Roles->userMenuItems());

        // Controller dependant variables
        $this->set('entityName', isset($this->entityName) ? $this->entityName : '');
        $this->set('entityNamePlural', isset($this->entityNamePlural) ? $this->entityNamePlural : '');

        // Relocating names to display into Table
        $displayName = $this->getDisplayName();
        $displayNamePlural = $this->getDisplayName("plural");

        $this->set('displayName', isset($displayName) ? $displayName : '');
        $this->set('displayNamePlural', isset($displayNamePlural) ? $displayNamePlural : '');

        // Used by SeoBehavior
        if ($this->{$this->getName()}->behaviors()->has('Seo')) {
            $this->set('seo', $this->{$this->getName()}->getSeoConfig());
        }

        // Used by Media Manager
        if (
            $this->{$this->getName()}->behaviors()->has('Media') &&
            (!isset($viewVars['multimedia']) || empty($viewVars['multimedia']))
        ) {
            $this->set('multimedia', $this->{$this->getName()}->getMediaConfig());
        }

        // Set colors to the view
        $this->set('colors', $this->getColors());

        // Control Session timeouts
        if (!$this->request->getSession()->check('last_activity')) {
            $this->request->getSession()->write('last_activity', time());
        }

        $last_activity = $this->request->getSession()->read('last_activity');
        if (time() - $last_activity > 60) {
            $this->request->getSession()->renew();
        }

        $this->request->getSession()->write('last_activity', time());
    }

    /**
     * Method to allow or deny access to the controllers in the application
     * If return false, only '$this->Auth->allow' actions will be allowed
     * If return true, all actions will be allowed
     * You can set which methods you want to allow or deny in each controller
     * overriding this method.
     *
     * @param Object $user to check if is allowed, object is AdminUser
     *
     * @return bool true or false whether if we want to allow or deny actions
     */
    public function isAuthorized($user)
    {
        if (
            $this->request->getParam('controller') === 'Pages' ||
            ($this->request->getParam('controller') === 'AdminUsers' && $this->request->getParam('action') === 'logout')
        ) {
            return true;
        }

        $rolesTable = TableRegistry::getTableLocator()->get('AdminUserRoles');
        $role =  $rolesTable->getRoleFromUser($user);

        // Admin can access every action
        // if user not admin, check privileges from role database table array and rol entities
        if (isset($role) && ($role['is_admin'] || $this->Roles->checkAuthorized($role))) {
            return true;
        }

        // If user does not have role actions for entity or entity is not rol, deny access.
        $this->Flash->error('No tienes permisos para realizar la acción solicitada.');

        return false;
    }

    /**
     * Change boolean property through ajax
     * Required Booleable Behavior declared in Model/Table.
     *
     * @param  $entityID         // of the entity
     * @param  $field_name // name of the boolean field to change
     * @param  $locale     // the language to change the boolean property
     *
     * @return Response
     */
    public function changeBoolean($entityID, $fieldName, $locale = null)
    {
        $this->setLocale($locale);
        $this->disableAutoRender();
        $response = $this->response->withType('json');
        $data = [];

        try {
            //recover the entity
            $entity = $this->{$this->getName()}->get($entityID);

            if ($this->{$this->getName()}->changeBoolean($entity, $fieldName)) {
                $data['status'] = 'success';
                $data['message'] = 'Propiedad cambiada correctamente.';
            }
        } catch (Exception $e) {
            $response = $response->withStatus($e->getCode());
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
        }

        $response = $response->withStringBody(json_encode($data));

        return $response;
    }

    /**
     * Change sort property through ajax
     * Required DraggableBehavior declared in Model/Table.
     *
     * @param  $entityID         //of the entity
     * @param  $sort       //new order of the entity
     * @param  $sort_field //the field to sort if we don't want to use the default
     *
     * @return Response
     */
    public function changeSort($entityID = null, $sort = null, $sortField = null)
    {
        $this->disableAutoRender();
        $response = $this->response->withType('json');
        $data = [];

        try {
            //recover the entity
            $entity = $this->{$this->getName()}->get($entityID);

            if ($this->{$this->getName()}->changeSort($entity, $sort, $sortField)) {
                $data['status'] = 'success';
                $data['message'] = 'Ordenación cambiada correctamente.';
            }
        } catch (Exception $e) {
            $response = $response->withStatus($e->getCode());
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
        }

        $response = $response->withStringBody(json_encode($data));

        return $response;
    }

    /**
     * Reorder a tree element up or down.
     *
     * @param  $entityID  //of the element to move
     * @param  $dir //direction to move the element
     *
     * @return Response
     */
    public function move($entityID = null, $dir = null)
    {
        // Get the referer url without the query
        $referer = parse_url($this->referer());
        $refererUrl =  Configure::read('Config.base_url') . substr($referer['path'], 1);

        //recover the entity
        $entity = $this->{$this->getName()}->get($entityID);
        if (!$entity) {
            return $this->redirect($refererUrl);
        }

        switch ($dir) {
            case 'up':
                $this->{$this->getName()}->moveUp($entity);
                break;
            case 'down':
                $this->{$this->getName()}->moveDown($entity);
                break;
        }

        return $this->redirect($refererUrl);
    }

    /**
     * Export the data of an entity in a CSV file.
     *
     * @return Response
     */
    public function exportCsv($items = null)
    {
        if ($this->{$this->getName()}->behaviors()->has('Exportable')) {
            //if behavior loaded, get export file content
            $fileContent = $this->{$this->getName()}->export($items);

            //if content not empty, export to file
            if ($fileContent !== false) {
                $now = new Time();
                $filename = $now->i18nFormat('yyyyMMddHHmmss') . '-' . $this->entityNamePlural . '.csv';
                return $this->response
                    ->withType('csv')
                    ->withCharset('UTF-8')
                    ->withStringBody($fileContent)
                    ->withDownload($filename);
            }

            $this->Flash->error('Ha habido un error exportando los datos. Por favor, inténtalo de nuevo más tarde');

            return $this->redirect($this->referer());
        }

        $this->Flash->error('Los datos de "' . $this->entityNamePlural . '" no se pueden exportar.');

        return $this->redirect($this->referer());
    }

    /**
     * Import a list of entities from a CSV file
     *
     *
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function import()
    {
        if ($this->{$this->getName()}->behaviors()->has('Importable')) {
            $entity = $this->{$this->getName()}->newEmptyEntity();
            if ($this->request->is(['patch', 'post', 'put'])) {
                if ($this->{$this->getName()}->importCsv($this->request->getData('file'))) {
                    $this->Flash->success('Importación realizada correctamente.');
                    return $this->redirect(
                        [
                            'action' => 'index'
                        ]
                    );
                }

                $this->Flash->error('Ha habido un error realizando la importación.
                    Por favor, revisa el fichero e inténtalo de nuevo.');
                return $this->redirect(
                    [
                        'action' => 'index'
                    ]
                );
            }

            $this->set("import_fields", $this->{$this->getName()}->getImportableFields());
            $this->set(compact('entity'));

            return $this->render('Pages/import');
        }
    }

    /**
     * Get available colours to configure the background and text color.
     *
     * @return array with the available colours
     */
    protected function getColors()
    {
        $colors = Configure::read('UI.colors');

        $aux_colors = [];
        foreach ($colors as $color) {
            $aux_colors[$color['suffix']] = $color['name'];
        }

        return $aux_colors;
    }

    /**
     * Change the locale of an entity.
     *
     * @param string $locale with the locale code
     */
    protected function setLocale($locale = null)
    {
        if ($locale != null && in_array($locale, array_keys(Configure::read('I18N.locales')))) {
            I18n::setLocale($locale);
        } else {
            I18n::setLocale(Configure::read('I18N.language'));
        }
    }

    /**
     * Get the configuration for the buttons of the entities in the desired table.
     *
     * @return array with the config of the buttons
     */
    protected function getTableButtons()
    {
        return $this->Roles->composeUserOptions($this->tableButtons);
    }

    /**
     * Get the configuration for the buttons in the header of the desired view.
     *
     * @param null $entity
     * @return array with the config of the buttons
     */
    protected function getHeaderActions($entity = null)
    {
        $aux_actions = [];
        foreach ($this->header_actions as $name => $config) {
            $aux_config = $config;
            if ($entity != null) {
                array_push($aux_config['url'], $entity->id);
            }

            if (isset($config['i18n']) && $config['i18n']) {
                array_push($aux_config['url'], I18n::getLocale());
            }

            $aux_actions[$name] = $aux_config;
        }

        return $this->Roles->composeUserOptions($aux_actions);
    }

    /**
     * Get the configuration for the tab buttons in the desired view.
     *
     * @param string $controller
     * @param string $action
     * @param null $entity
     * @return array with the config of the buttons
     */
    protected function getTabActions($controller = '', $action = '', $entity = null)
    {
        $aux_actions = [];
        if (isset($this->tabActions) && !empty($this->tabActions)) {
            foreach ($this->tabActions as $name => $config) {
                if ($config['url']['controller'] == $controller && $config['url']['action'] == $action) {
                    $config['current'] = 'current';
                }
                if ($entity != null && $config['url']['controller'] != 'Slides') {
                    array_push($config['url'], $entity->id);
                }
                // if (!$config['i18n']) {
                //     array_push($config['url'], I18n::getLocale());
                // }
                $aux_actions[$name] = $config;
            }
        }

        if ($this->{$this->getName()}->behaviors()->has("Parameters")) {
            $entity_plugin = $this->{$this->getName()}->getEntityPlugin();
            $aux_actions['Configuración avanzada'] = [
                'url' => [
                    'controller' => $controller,
                    'action' => 'parameters',
                    'plugin' => $entity_plugin
                ],
                'current' => '',
                'i18n' => true
            ];
        }

        return $this->Roles->composeUserTabs($aux_actions);
    }

    /**
     * Return the entity Name of the class, defined in the controller
     *
     * @param string $variant
     * @return string the name entity
     */
    public function getDisplayName($variant = "singular")
    {
        if (method_exists($this->{$this->getName()}, "getDisplayName")) {
            return $this->{$this->getName()}->getDisplayName($variant);
        } elseif (isset($this->entity_name_plural) && isset($this->entity_name)) {
            if ($variant == "plural") {
                return $this->entity_name_plural;
            } else {
                return $this->entity_name;
            }
        }

        return false;
    }

    /**
     * Return all plugins in Colmena admin
     * 
     * @return array name of the plugins
     */
    public function getPlugins()
    {
        $plugins = Configure::read('plugins');
        unset($plugins['Mailgun']);
        unset($plugins['CakePdf']);

        return $plugins;
    }
}
