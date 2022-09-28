<?php

namespace App\Controller;

use App\Model\Table\AdminUsersTable;
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * AdminUserRoles Controller
 *
 * @property AdminUserRolesTable $AdminUserRoles
 */
class AdminUserRolesController extends AppController
{
    public $entityName = 'roles de usuario';
    public $entityNamePlural = 'roles de usuarios';

    public $tableButtons = [
        'Editar' => [
            'url' => [
                'controller' => 'AdminUserRoles',
                'action' => 'edit',
                'plugin' => false
            ],
            'options' => [
                'class' => 'button'
            ]
        ],
        'Borrar' => [
            'url' => [
                'controller' => 'AdminUserRoles',
                'action' => 'delete',
                'plugin' => false
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar el rol de usuario?',
                'class' => 'button'
            ]
        ]
    ];

    public $header_actions = [
        'Añadir rol de usuario' => [
            'url' => [
                'controller' => 'AdminUserRoles',
                'plugin' => false,
                'action' => 'add'
            ]
        ]
    ];

    public $tabActions = [
        'Usuarios' => [
            'url' => [
                'controller' => 'AdminUsers',
                'action' => 'index',
                'plugin' => false
            ],
            'current' => ''
        ],
        'Roles de usuario' => [
            'url' => [
                'controller' => 'AdminUserRoles',
                'action' => 'index',
                'plugin' => false
            ],
            'current' => ''
        ]
    ];

    public $roles_tabActions = [
        'Datos del rol' => [
            'url' => [
                'controller' => 'AdminUserRoles',
                'action' => 'edit',
                'plugin' => false
            ],
            'current' => ''
        ],
        'Permisos del usuario' => [
            'url' => [
                'controller' => 'AdminUserRoles',
                'action' => 'userRolesPermissions',
                'plugin' => false
            ],
            'current' => ''
        ]
    ];

    // Default pagination settings
    public $paginate = [
        'limit' => 20,
        'order' => [
            'AdminUserRoles.id' => 'ASC'
        ]
    ];

    /**
     * Index method
     *
     * @param  string $keyword keyword for search
     *
     * @return Response
     */
    public function index($keyword = null)
    {
        if ($this->request->is('post')) {
            //recover the keyword
            $keyword = $this->request->getData('keyword');
            //re-send to the same controller with the keyword
            return $this->redirect(['action' => 'index', $keyword]);
        }

        // Paginator
        $settings = $this->paginate;
        // If performing search, there is a keyword
        if ($keyword != null) {
            // Change pagination conditions for searching
            $settings['conditions'] = [
                'OR' => [
                    $this->getName() . '.name LIKE' => '%' . $keyword . '%'
                ]
            ];
        }

        //prepare the pagination
        $this->paginate = $settings;

        $entities = $this->paginate($this->modelClass);

        //compose buttons available based on user permissions
        $tableButtons = $this->Roles->composeUserOptions($this->tableButtons);
        $header_actions = $this->Roles->composeUserOptions($this->header_actions);

        $this->set('header_actions', $header_actions);
        $this->set('tableButtons', $tableButtons);
        $this->set('tabActions', $this->getTabActions('AdminUserRoles', 'index'));
        $this->set('entities', $entities);
        $this->set('_serialize', 'entities');
        $this->set('keyword', $keyword);
    }

    /**
     * Add method
     *
     * @return Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $entity = $this->{$this->getName()}->newEmptyEntity();

        if ($this->request->is('post')) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El rol de usuario se ha guardado correctamente.');
                return $this->redirect(['action' => 'user_roles_permissions', $entity->id]);
            } else {
                $this->Flash->error('El rol de usuario no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }
        $this->set(compact('entity'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Company id.
     * @return Response|null Redirects on successful edit, renders view otherwise.
     */
    public function edit($id = null)
    {
        $entity = $this->{$this->getName()}->get($id);
        if (!isset($entity) || is_null($entity)) {
            $this->Flash->error('Rol de usuario no encontrado');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
          
            $entity = $this->{$this->getName()}->patchEntity($entity, $data);
            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El rol de usuario se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('El rol de usuario no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }

        $this->set('tabActions', $this->getRolesTabActions('AdminUserRoles', 'edit', $entity));
        $this->set(compact('entity'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Company id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->{$this->getName()}->get($id);
        if ($this->{$this->getName()}->delete($entity)) {
            $this->Flash->success('El rol de usuario se ha borrado correctamente.');
        } else {
            $this->Flash->error('El rol de usuario no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * User roles permissions view for the current client.
     *
     * @param int $role_id
     * @return void
     */
    public function userRolesPermissions($role_id = null)
    {
        $role = $this->{$this->getName()}->get($role_id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->{$this->getName()}->createUserRolesPermissions($role_id, $this->request->getData())) {
                $this->Flash->success('Los permisos del usuario se han modificado correctamente.');
            } else {
                $this->Flash->error('Los permisos del usuario no se han modificado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }

        $permissions = $this->{$this->getName()}->AdminUserRolesPermissions->find('all', [
            'conditions' => ['role_id' => $role_id],
            'order' => 'id'
        ]);

        $rolableEntities = Configure::read('Roles.rolable_entities');
        $possiblePermissions = array_merge(['none' => 'Ninguno'], Configure::read('Roles.possible_permissions'));

        $entities = [];
        foreach ($rolableEntities as $entity => $name) {
            $entities[$entity] = [
                'name' => $name,
                'actions' => 'none'
            ];
            foreach ($permissions as $permission) {
                if ($permission->model == $entity) {
                    $entities[$entity]['actions'] = $permission->actions;
                    break;
                }
            }
        }

        $this->set('entities', $entities);
        $this->set('possiblePermissions', $possiblePermissions);
        $this->set('tabActions', $this->getRolesTabActions('AdminUserRoles', 'userRolesPermissions', $role));
        $this->set('currentRole', $role);
    }

    /**
     * Get the configuration for the tab buttons in the edit view
     *
     * @param  string $controller name of the current controller
     * @param  string $action     name of the current action
     * @param  Entity $entity     the entity to add its id to the actions
     *
     * @return array with the config of the buttons
     */
    protected function getRolesTabActions($controller = '', $action = '', $entity = null)
    {
        $aux_actions = [];
        foreach ($this->roles_tabActions as $name => $config) {
            if ($config['url']['controller'] == $controller && $config['url']['action'] == $action) {
                $config['current'] = 'current';
            }
            if ($entity != null && $config['url']['controller'] != 'Slides') {
                array_push($config['url'], $entity->id);
            }
            
            $aux_actions[$name] = $config;
        }

        return $this->Roles->composeUserTabs($aux_actions);
    }
}
