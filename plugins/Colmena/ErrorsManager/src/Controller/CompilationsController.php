<?php

namespace Colmena\ErrorsManager\Controller;

use Colmena\ErrorsManager\Controller\AppController;
use App\Encryption\EncryptTrait;

class CompilationsController extends AppController
{
    use EncryptTrait;

    public $entityName = 'compilacion';
    public $entityNamePlural = 'compilaciones';

    // Default pagination settings
    public $paginate = [
        'limit' => 10,
        'order' => [
            'id' => 'DESC'
        ],
        'contain' => [
            'Markers',
            'Session',
            'Student'
        ]
    ];

    protected $tableButtons = [
        'Editar' => [
            'icon' => '<i class="fal fa-edit"></i>',
            'url' => [
                'controller' => 'Compilations',
                'action' => 'edit',
                'plugin' => 'Colmena/ErrorsManager'
            ],
            'options' => [
                'class' => 'green-icon',
                'escape' => false
            ]
        ],
        'Borrar' => [
            'icon' => '<i class="fal fa-trash-alt"></i>',
            'url' => [
                'controller' => 'Compilations',
                'action' => 'delete',
                'plugin' => 'Colmena/ErrorsManager'
            ],
            'options' => [
                'confirm' => '¿Estás seguro de que quieres eliminar la compilación?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ],
        'Markers' => [
            'icon' => '<i class="fal fa-bug"></i>',
            'url' => [
                'controller' => 'Compilations',
                'action' => 'markers',
                'plugin' => 'Colmena/ErrorsManager'
            ],
            'options' => [
                'class' => 'gray-icon',
                'escape' => false
            ]
        ],
    ];

    protected $header_actions = [];
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
            'add'
        ]);
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

        $this->set('header_actions', $this->getHeaderActions());
        $this->set('tableButtons', $this->getTableButtons());
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
        $compilation = $this->{$this->getName()}->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $user = $this->{$this->getName()}->Student->find('all')->where(['identifier' => $data['user_id']])->first();

            if (!isset($user)) {
                $user = $this->{$this->getName()}->Student->newEntity([
                    'identifier' => $data['user_id'],
                    'name' => '-',
                    'surname' => '-'
                ]);

                $user = $this->{$this->getName()}->Student->save($user);
            }

            $data['user_id'] = $user->id;

            $compilation = $this->{$this->getName()}->patchEntity($compilation, $data);
            $compilation = $this->{$this->getName()}->save($compilation);

            $compilation = $this->linkSession($compilation);

            if (!isset($compilation)) {
                $this->showErrors($compilation);
            }
        }

        $compilationID = $compilation->id;

        $this->set(compact('compilationID'));
        $this->set('_serialize', 'compilationID');
    }

    /**
     * Function with links the compilation with the corresponding session.
     * If there are more than one, it creates a conflict that will be
     * solved by the user
     *
     * @param [Compilation] $compilation
     * @return void
     */
    private function linkSession($compilation)
    {
        $dateParts = explode(' ', $compilation->timestamp);
        $date = date('Y-m-d', strtotime($dateParts[0])); // Y-m-d
        $hour = date('H:i:s', strtotime($dateParts[1])); // H:i:s

        $session = $this->{$this->getName()}->Session
            ->find('all')
            ->matching('SessionSchedules.PracticeGroups.Users', function ($q) use ($date, $hour, $compilation) {
                return $q->where(['SessionSchedules.date' => $date, 'SessionSchedules.end_hour >=' => $hour, 'SessionSchedules.start_hour <=' => $hour, 'Users.id' => $compilation->user_id]);
            })->first();

        $newEntity = [];
        if (isset($session)) {
            $newEntity['session_id'] = $session->id;
        }

        $compilation = $this->{$this->getName()}->patchEntity($compilation, $newEntity);
        $compilation = $this->{$this->getName()}->save($compilation);

        return $compilation;
    }

    /**
     * Edit method
     *
     * @param string|null $entityID Product id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($entityID = null, $locale = null)
    {
        $this->setLocale($locale);

        $projectID = $this->getSessionProject();
        $sessions = $this->{$this->getName()}->Session->find('list')->toArray();

        $entity = $this->{$this->getName()}->find('all')->where([$this->getName() . '.id' => $entityID])->contain([
            'Markers',
            'Session',
            'Student'
        ])->first();

        $students = $this->{$this->getName()}->Student->find('list')
            ->matching('Groups.Schedules.Sessions.Subjects.Projects', function ($q)  use ($projectID) {
                return $q->where(['Projects.id =' => $projectID]);
            })->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('La compilación se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id, $locale]);
            }

            $this->showErrors($entity);
        }

        $this->set('tabActions', $this->getTabActions('Users', 'edit', $entity));
        $this->set(compact('entity', 'sessions', 'students'));
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
            $this->Flash->success('La compilación se ha borrado correctamente.');
        } else {
            $this->Flash->error('La compilación no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Makers method
     *
     * @param [int] $compilationID
     * @return void
     */
    public function markers($compilationID)
    {
        $compilation = $this->{$this->getName()}->get($compilationID);
        $entities = $this->{$this->getName()}->Markers->find('all')->contain(['Error', 'Session', 'Student'])->where(['compilation_id' => $compilationID])->toArray();

        $this->set(compact('entities', 'compilation'));
    }

    /**
     * Function which shows the entity error's on saving
     *
     * @param [Session] $entity
     * @return void
     */
    private function showErrors($entity)
    {
        $errorMsg = '<p>La compilación no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';

        foreach ($entity->getErrors() as $error) {
            $errorMsg .= '<p>' . $error['message'] . '</p>';
        }

        $this->Flash->error($errorMsg, ['escape' => false]);
    }

    /**
     * Function which obtains the project id stored in session
     *
     * @return projectID
     */
    private function getSessionProject()
    {
        $session = $this->request->getSession();
        $projectID = $session->read('Projectid');

        return $projectID;
    }
}
