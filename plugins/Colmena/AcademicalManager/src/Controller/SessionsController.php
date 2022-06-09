<?php

namespace Colmena\AcademicalManager\Controller;

use Colmena\AcademicalManager\Controller\AppController;
use App\Encryption\EncryptTrait;
use Cake\Core\Configure;
use Cake\Http\Session;

class SessionsController extends AppController
{
    use EncryptTrait;

    public $entity_name = 'sesion';
    public $entity_name_plural = 'sesiones';

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
                'controller' => 'Sessions',
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
                'controller' => 'Sessions',
                'action' => 'delete',
                'plugin' => 'Colmena/AcademicalManager'
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar la sesión?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ]
    ];

    protected $header_actions = [
        'Añadir sesión' => [
            'url' => [
                'controller' => 'Sessions',
                'plugin' => 'Colmena/AcademicalManager',
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
        $this->Auth->allow([]);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($subjectID, $keyword = null)
    {
        if (isset($subjectID)) {
            $sessions = $this->{$this->getName()}
                ->find('all')
                ->matching('Subjects', function ($q) use ($subjectID) {
                    return $q->where(['Subjects.id' => $subjectID]);
                });
        }

        if ($this->request->is('post')) {
            //recover the keyword
            $keyword = $this->request->getData('keyword');
            //re-send to the same controller with the keyword
            return $this->redirect(['action' => 'index/' . $subjectID, $keyword]);
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

        $entities = $this->paginate($sessions);
        $subject = $this->{$this->getName()}->Subjects->get($subjectID);

        $this->set('header_actions', $this->getHeaderActions());
        $this->set('table_buttons', $this->getTableButtons());
        $this->set('entities', $entities);
        $this->set('subject', $subject);
        $this->set('keyword', $keyword);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add($subjectID)
    {
        $entity = $this->{$this->getName()}->newEmptyEntity();
        $subject = $this->{$this->getName()}->Subjects->get($subjectID);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['subject_id'] = $subjectID;
            $entity = $this->{$this->getName()}->patchEntity($entity, $data);

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('la sesión se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id]);
            } else {
                $error_msg = '<p>La sesión no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
                foreach ($entity->errors() as $field => $error) {
                    $error_msg .= '<p>' . $error['message'] . '</p>';
                }
                $this->Flash->error($error_msg, ['escape' => false]);
            }
        }

        $this->set(compact('entity', 'subject'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $subjectID, $locale = null)
    {
        $this->setLocale($locale);
        $entity = $this->{$this->getName()}->get($id);
        $subject = $this->{$this->getName()}->Subjects->get($subjectID);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('la sesión se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id, $locale]);
            } else {
                $error_msg = '<p>La sesión no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
                foreach ($entity->errors() as $field => $error) {
                    $error_msg .= '<p>' . $error['message'] . '</p>';
                }
                $this->Flash->error($error_msg, ['escape' => false]);
            }
        }

        $this->set('tab_actions', $this->getTabActions('Users', 'edit', $entity));
        $this->set(compact('entity', 'subject'));
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
            $this->Flash->success('la sesión se ha borrado correctamente.');
        } else {
            $this->Flash->error('la sesión no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
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
     * Method which lists the Sessions
     *
     * @return the list of Sessions assigned to the project
     */
    public function list()
    {
        $projectID = $this->request->getData('id');
        $query = $this->{$this->getName()}->find('all');

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
     * Method which is used to show the user the groups and its schedules
     *
     * @param [int] $sessionID
     * @param [int] $subjectID
     * @return void
     */
    public function listSessionSchedules($sessionID, $subjectID){
        $subject = $this->{$this->getName()}->Subjects->get($subjectID);
        $session = $this->{$this->getName()}->get($sessionID);

        $entities = $this->{$this->getName()}->SessionSchedules->find('all')->where(['session_id' => $sessionID])->toList();

        $this->set(compact('session', 'subject'));
    }
}
