<?php

namespace Colmena\AcademicalManager\Controller;

use Colmena\AcademicalManager\Controller\AppController;
use App\Encryption\EncryptTrait;
use Cake\Core\Configure;

class ProjectsController extends AppController
{
    use EncryptTrait;

    public $entity_name = 'proyecto';
    public $entity_name_plural = 'proyectos';

    // Default pagination settings
    public $paginate = [
        'limit' => 20,
        'order' => [
            'id' => 'ASC'
        ]
    ];

    protected $table_buttons = [
        'Editar' => [
            'icon' => '<i class="fas fa-edit"></i>',
            'url' => [
                'controller' => 'Projects',
                'action' => 'edit',
                'plugin' => 'Colmena/AcademicalManager'
            ],
            'options' => [
                'class' => 'green-icon',
                'escape' => false
            ]
        ],
        'Borrar' => [
            'icon' => '<i class="fas fa-trash-alt"></i>',
            'url' => [
                'controller' => 'Projects',
                'action' => 'delete',
                'plugin' => 'Colmena/AcademicalManager'
            ],
            'options' => [
                'confirm' => '¿Estás seguro de que quieres eliminar el proyecto?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ]
    ];

    protected $header_actions = [
        'Añadir proyecto' => [
            'url' => [
                'controller' => 'Projects',
                'plugin' => 'Colmena/AcademicalManager',
                'action' => 'add'
            ]
        ],
    ];

    protected $tab_actions = [];

    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event The beforeFilter event.
     *
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['list']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($keyword = null)
    {
        $projectID = $this->getSessionProject();
        $userID = $this->Auth->user()['id'];

        if ($this->request->is('post')) {
            //recover the keyword
            $keyword = $this->request->getData('keyword');
            //re-send to the same controller with the keyword
            return $this->redirect(['action' => 'index', $keyword]);
        }

        // Add the project id to the pagination
        $this->paginate['project_id'] = $projectID;

        // Paginator
        $settings = $this->paginate;

        // If performing search, there is a keyword
        if ($keyword != null) {
            // Change pagination conditions for searching
            $settings['conditions'] = [
                'OR' => [
                    $this->getName() . '.name LIKE' => '%' . $keyword . '%',
                ]
            ];
        }

        $userCondition = ['user_id' => $userID];
        if (isset($settings['conditions'])) {
            array_push($userCondition, $settings['conditions']);
        } else {
            $settings['conditions'] = $userCondition;
        }

        //prepare the pagination
        $this->paginate = $settings;


        $entities = $this->paginate($this->modelClass);

        $this->set('header_actions', $this->getHeaderActions());
        $this->set('table_buttons', $this->getTableButtons());
        $this->set('entities', $entities);
        $this->set('_serialize', 'entities');
        $this->set('keyword', $keyword);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userID = $this->Auth->user()['id'];
        $entity = $this->{$this->getName()}->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = $userID;

            $entity = $this->{$this->getName()}->patchEntity($entity, $data);
            $project = $this->{$this->getName()}->save($entity);

            if (isset($project)) {
                $this->startSession($project['id']);
                $this->Flash->success('El proyecto se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id]);
            }

            $errorMsg = '<p>El proyecto no se ha guardado. Por favor, revisa los datos e inténtalo de nuevo.</p>';
            foreach ($entity->errors() as $error) {
                $errorMsg .= '<p>' . $error['message'] . '</p>';
            }
            $this->Flash->error($errorMsg, ['escape' => false]);
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
                $this->Flash->success('el proyecto se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id, $locale]);
            }

            $errorMsg = '<p>el proyecto no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
            foreach ($entity->errors() as $error) {
                $errorMsg .= '<p>' . $error['message'] . '</p>';
            }
            $this->Flash->error($errorMsg, ['escape' => false]);
        }

        $this->set('tab_actions', $this->getTabActions('Users', 'edit', $entity));
        $this->set(compact('entity'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->{$this->getName()}->get($id);
        if ($this->{$this->getName()}->delete($entity)) {
            $this->Flash->success('el proyecto se ha borrado correctamente.');
        } else {
            $this->Flash->error('el proyecto no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Method which lists the projects
     *
     * @return the list of projects assigned to the user
     */
    public function list()
    {
        $userID = $this->request->getData('id');
        $query = $this->{$this->getName()}->find('all');

        if (isset($userID)) {
            $query = $query->where(['user_id' => $userID]);
        }

        $entities = $query->toList();
        $content = json_encode($entities);

        $this->response = $this->response->withStringBody($content);
        $this->response = $this->response->withType('json');

        return $this->response;
    }

    /**
     * Method which returns the project from the session
     */
    private function getSessionProject()
    {
        $session = $this->request->getSession();
        $projectID = $session->read('Projectid');

        return $projectID['projectID'];
    }

    /**
     * Method which puts the project Id into the session
     *
     * @return void
     */
    public function startSession($projectID)
    {
        $session = $this->request->getSession();
        $response = $this->response->withType('json');
        $session->write('Projectid', $projectID);

        Configure::write('Session.project', $projectID);
        return $response->withStringBody(json_encode($projectID));
    }
}
