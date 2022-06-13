<?php

namespace Colmena\UsersManager\Controller;

use Colmena\AcademicalManager\Controller\AppController;
use App\Encryption\EncryptTrait;
use Cake\Core\Configure;
use Cake\Http\Session;

class PracticeGroupsController extends AppController
{
    use EncryptTrait;

    public $entity_name = 'grupo de practicas';
    public $entity_name_plural = 'grupos de practicas';

    // Default pagination settings
    public $paginate = [
        'limit' => 20,
        'order' => [
            'id' => 'DESC'
        ]
    ];

    protected $table_buttons = [
        'Editar' => [
            'icon' => '<i class="fas fa-edit"></i>',
            'url' => [
                'controller' => 'PracticeGroups',
                'action' => 'edit',
                'plugin' => 'Colmena/UsersManager'
            ],
            'options' => [
                'class' => 'green-icon',
                'escape' => false
            ]
        ],
        'Borrar' => [
            'icon' => '<i class="fas fa-trash-alt"></i>',
            'url' => [
                'controller' => 'PracticeGroups',
                'action' => 'delete',
                'plugin' => 'Colmena/UsersManager'
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar la asignatura?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ]
    ];

    protected $header_actions = [
        'Añadir grupo de prácticas' => [
            'url' => [
                'controller' => 'PracticeGroups',
                'plugin' => 'Colmena/UsersManager',
                'action' => 'add'
            ]
        ]
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
        $this->Auth->allow(['list', 'listSubjectById']);
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
                ]
            ];
        }

        //prepare the pagination
        $this->paginate = $settings;

        $entities = $this->paginate($this->modelClass);

        debug($entities);die;

        $this->set('header_actions', $this->getHeaderActions());
        $this->set('table_buttons', $this->getTableButtons());
        $this->set('entities', $entities);
        $this->set('keyword', $keyword);
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
            $name = $this->request->getData('name');
            $usersIDs = $this->request->getData('user_id')['_ids'];

            foreach ($usersIDs as $userID) {
                $data['name'] = $name;
                $data['user_id'] = $userID;

                $entity = $this->{$this->getName()}->newEmptyEntity();
                $entity = $this->{$this->getName()}->patchEntity($entity, $data);
                $result = $this->{$this->getName()}->save($entity);
                echo '<pre>', var_dump($result), '</pre>';
            }

            if ($result) {
                $this->Flash->success('El grupo de prácticas se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $error_msg = '<p>El grupo de prácticas no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';

                foreach ($entity->getErrors() as $field => $error) {
                    $error_msg .= '<p>' . $error['message'] . '</p>';
                }

                $this->Flash->error($error_msg, ['escape' => false]);
            }
        }

        $students = $this->{$this->getName()}->Users->find('list')->toArray();

        $this->set(compact('entity', 'students'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $locale = null)
    {
        $this->setLocale($locale);
        $entity = $this->{$this->getName()}->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('la asignatura se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id, $locale]);
            } else {
                $error_msg = '<p>La asignatura no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
                foreach ($entity->errors() as $field => $error) {
                    $error_msg .= '<p>' . $error['message'] . '</p>';
                }
                $this->Flash->error($error_msg, ['escape' => false]);
            }
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
            $this->Flash->success('la asignatura se ha borrado correctamente.');
        } else {
            $this->Flash->error('la asignatura no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    private function getSessionProject()
    {
        $session = $this->request->getSession();
        $projectID = $session->read('Projectid');

        return $projectID['projectID'];
    }

    /**
     * Method which lists the subjects
     *
     * @return list of subjects assigned to the project
     */
    public function list()
    {
        $projectID = $this->request->getData('id');
        $query = $this->{$this->getName()}->find('all')->contain(['Sessions']);

        if (isset($projectID)) {
            $query->matching('Projects', function ($q)  use ($projectID) {
                return $q->where(['Projects.id =' => $projectID]);
            });
        }

        $entities = $query->toList();
        $content = json_encode($entities);

        $this->response = $this->response->withStringBody($content);
        $this->response = $this->response->withType('json');

        return $this->response;
    }

    /**
     * Function which lists the subject by its id
     *
     * @return subject
     */
    public function listSubjectById()
    {
        $subjectID = $this->request->getData('id');
        $query = $this->{$this->getName()}->find('all')->where(['id' => $subjectID])->contain(['Sessions'])->first();

        $this->response = $this->response->withStringBody($query);
        $this->response = $this->response->withType('json');

        return $this->response;
    }

    /**
     * Function which returns the subjects associated to users' id     
     * */
    public function listSubjectByStudentId()
    {
        $studentId = $this->request->getData('id');
        $query = $this->{$this->getName()}->find('all')->where(['id' => $studentId])->contain(['Sessions'])->first();

        $this->response = $this->response->withStringBody($query);
        $this->response = $this->response->withType('json');

        return $this->response;
    }
}