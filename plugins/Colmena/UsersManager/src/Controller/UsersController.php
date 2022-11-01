<?php

namespace Colmena\UsersManager\Controller;

use Colmena\UsersManager\Controller\AppController;
use Cake\Event\Event;
use App\Encryption\EncryptTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class UsersController extends AppController
{
    use EncryptTrait;

    public $entityName = 'alumno';
    public $entityNamePlural = 'alumnos';

    // Default pagination settings
    public $paginate = [
        'limit' => 10,
        'order' => [
            'id' => 'ASC'
        ]
    ];

    protected $tableButtons = [
        'Editar' => [
            'icon' => '<i class="fal fa-edit"></i>',
            'url' => [
                'controller' => 'Users',
                'action' => 'edit',
                'plugin' => 'Colmena/UsersManager'
            ],
            'options' => [
                'class' => 'green-icon',
                'escape' => false
            ]
        ],
        'Borrar' => [
            'icon' => '<i class="fal fa-trash-alt"></i>',
            'url' => [
                'controller' => 'Users',
                'action' => 'delete',
                'plugin' => 'Colmena/UsersManager'
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar el usuario?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ]
    ];

    protected $header_actions = [
        'Añadir alumno' => [
            'url' => [
                'controller' => 'Users',
                'plugin' => 'Colmena/UsersManager',
                'action' => 'add'
            ]
        ],
    ];

    protected $tabActions = [];

    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event The beforeFilter event.
     *
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'login', 'register'
        ]);
    }

    /**
     * Method used for making the user login request
     *
     * @return void
     */
    public function login()
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post']);

        $data = $this->request->getData();
        $response = $this->response->withType('json');

        $user = $this->{$this->getName()}->login($data['username']);
        $passUser = $this->decrypt($user['password']);

        if ($data['password'] == $passUser) {
            $response = $response->withStringBody(json_encode($user));
        } else {
            throw new UnauthorizedException("Incorrect login data");
        }

        return $response;
    }

    /**
     * Method used for making the user login request
     *
     * @return void
     */
    public function register()
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post']);

        $data = $this->request->getData();
        $response = $this->response->withType('json');

        $entity = $this->{$this->getName()}->newEntity($data);
        $user = $this->{$this->getName()}->save($data);

        if (isset($user)) {
            $response = $response->withStringBody(json_encode($user));
        } else {
            throw new UnauthorizedException("Incorrect login data");
        }

        return $response;
    }

    /**
     * Index method
     *
     * @return void
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
                    $this->getName() . '.name LIKE' => '%' . $keyword . '%',
                    $this->getName() . '.email LIKE' => '%' . $keyword . '%',
                ]
            ];
        }

        $projectID = $this->getSessionProject();
        $users = $this->{$this->getName()}
            ->find('all')
            ->matching('Groups.Schedules.Sessions.Subjects.Projects', function ($q)  use ($projectID) {
                return $q->where(['Projects.id =' => $projectID]);
            });

        //prepare the pagination
        $this->paginate = $settings;
        $entities = $this->paginate($users);

        $this->set('header_actions', $this->getHeaderActions());
        $this->set('tableButtons', $this->getTableButtons());
        $this->set('entities', $entities);
        $this->set('_serialize', 'entities');
        $this->set('keyword', $keyword);
    }

    private function getSessionProject()
    {
        $session = $this->request->getSession();
        $projectID = $session->read('Projectid');

        return $projectID['projectID'];
    }
    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $entity = $this->{$this->getName()}->newEmptyEntity();
        if ($this->request->is('post')) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El usuario se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id]);
            }

            $this->showErrors($entity);
        }

        $this->set(compact('entity'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($entityID = null, $locale = null)
    {
        $this->setLocale($locale);
        $entity = $this->{$this->getName()}->get($entityID);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El usuario se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id, $locale]);
            }

            $this->showErrors($entity);
        }

        $this->set('tabActions', $this->getTabActions('Users', 'edit', $entity));
        $this->set(compact('entity'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($entityID = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->{$this->getName()}->get($entityID);
        if ($this->{$this->getName()}->delete($entity)) {
            $this->Flash->success('El usuario se ha borrado correctamente.');
        } else {
            $this->Flash->error('El usuario no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Function which shows the entity error's on saving
     *
     * @param [Session] $entity
     * @return void
     */
    private function showErrors($entity)
    {
        $errorMsg = '<p>El alumno no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';

        foreach ($entity->errors() as $error) {
            $errorMsg .= '<p>' . $error['message'] . '</p>';
        }

        $this->Flash->error($errorMsg, ['escape' => false]);
    }
}
