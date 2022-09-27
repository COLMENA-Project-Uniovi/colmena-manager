<?php

namespace App\Controller;

use App\Model\Table\AdminUsersTable;
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Http\Session;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property AdminUsersTable $AdminUsers
 */
class AdminUsersController extends AppController
{
    public $entityName = 'usuario de administración';
    public $entityNamePlural = 'usuarios de administración';

    public $table_buttons = [
        'Editar' => [
            'url' => [
                'controller' => 'AdminUsers',
                'action' => 'edit',
                'plugin' => false
            ],
            'options' => [
                'class' => 'button'
            ]
        ],
        'Borrar' => [
            'url' => [
                'controller' => 'AdminUsers',
                'action' => 'delete',
                'plugin' => false
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar el usuario de administración?',
                'class' => 'button'
            ]
        ]
    ];

    public $header_actions = [
        'Añadir usuario' => [
            'url' => [
                'controller' => 'AdminUsers',
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

    // Default pagination settings
    public $paginate = [
        'limit' => 20,
        'order' => [
            'AdminUsers.id' => 'ASC'
        ]
    ];

    /**
     * Method triggered before every action in the controller occurs.
     * Handy place to check for user autentification
     *
     * @param  \Cake\Event\Event $event
     *
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // If we are logged in and generating a new password
        if (
            $this->request->getParam('action') != 'login' &&
            $this->request->getParam('action') != 'logout' &&
            !$this->Auth->user()
        ) {
            // Deny access to all actions except login and logout
            return $this->redirect(['action' => 'login']);
        }
    }

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
                    $this->getName() . '.title LIKE' => '%' . $keyword . '%'
                ]
            ];
        }

        //prepare the pagination
        $this->paginate = $settings;

        $entities = $this->paginate($this->modelClass);

        foreach ($entities as $entity) {
            $entity['role_name'] = $this->{$this->getName()}->AdminUserRoles->get($entity['role_id'])->name;
        }

        //compose buttons available based on user permissions
        $table_buttons = $this->Roles->composeUserOptions($this->table_buttons);
        $header_actions = $this->Roles->composeUserOptions($this->header_actions);

        $this->set('header_actions', $header_actions);
        $this->set('table_buttons', $table_buttons);
        $this->set('tabActions', $this->getTabActions('AdminUsers', 'index'));
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
            $data = $this->request->getData();
            if (!isset($data['password_repeat']) || $data['password_repeat'] != $data['password']) {
                $this->Flash->error('Las contraseñas no coinciden.');
                return $this->redirect([
                    'action' => 'add',
                ]);
            }

            $entity = $this->{$this->getName()}->patchEntity($entity, $data);
            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El usuario de administración se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('El usuario de administración no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }

        $roles = $this->{$this->getName()}->AdminUserRoles->find('list');

        $this->set(compact('entity', 'roles'));
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
            $this->Flash->error('Usuario de administración no encontrado');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if ($data['password'] == '********') {
                unset($data['password']);
            } else if (!isset($data['password_repeat']) || $data['password_repeat'] != $data['password']) {
                $this->Flash->error('Las contraseñas no coinciden.');
                return $this->redirect([
                    'action' => 'edit',
                    $id
                ]);
            }

            $entity = $this->{$this->getName()}->patchEntity($entity, $data);
            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El usuario de administración se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('El usuario de administración no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }

        $roles = $this->{$this->getName()}->AdminUserRoles->find('list');

        $this->set(compact('entity', 'roles'));
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
            $this->Flash->success('El usuario de administración se ha borrado correctamente.');
        } else {
            $this->Flash->error('El usuario de administración no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Default login method
     *
     * @return Response
     */
    public function login()
    {
        $this->viewBuilder()->setLayout('login');
        $session = $this->request->getSession();

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();

            if ($user && $user['is_active'] == 1) {
                $this->Auth->setUser($user);

                $projectsTable = TableRegistry::getTableLocator()->get('acm_projects');
                $projects = $projectsTable->find('all')->where(['user_id' => $user['id']])->toArray();
                $session->write('Projects', $projects);

                return $this->redirect($this->Auth->redirectUrl());
            }

            if ($user && !$user['is_active']) {
                $this->Flash->error(
                    'La cuenta ha sido desactivada',
                    [
                        'key' => 'auth',
                        'clear' => true
                    ]
                );
            } else {
                $this->Flash->error(
                    'Los datos son incorrectos',
                    [
                        'key' => 'auth',
                        'clear' => true
                    ]
                );
            }
        } else {
            $this->Flash->default(
                'Para acceder al portal es necesario usuario y contraseña',
                [
                    'key' => 'auth',
                    'clear' => true
                ]
            );
        }
    }

    /**
     * Default logout method
     *
     * @return Response
     */
    public function logout()
    {
        $session = $this->request->getSession();
        $session->destroy();

        $this->Flash->success(
            'Has cerrado sesión correctamente.',
            ['key' => 'auth']
        );
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Method which puts the project Id into the session
     *
     * @return void
     */
    public function startSession()
    {
        $session = $this->request->getSession();
        $response = $this->response->withType('json');

        $projectID = $this->request->getData();
        $session->write('Projectid', $projectID);

        return $response->withStringBody(json_encode($projectID));
    }
}
