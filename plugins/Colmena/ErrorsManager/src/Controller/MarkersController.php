<?php

namespace Colmena\ErrorsManager\Controller;

use Colmena\ErrorsManager\Controller\AppController;
use App\Encryption\EncryptTrait;
use Cake\ORM\TableRegistry;

class MarkersController extends AppController
{
    use EncryptTrait;

    public $entityName = 'marker';
    public $entityNamePlural = 'markers';

    // Default pagination settings
    public $paginate = [
        'limit' => 20,
        'order' => [
            'Markers.id' => 'ASC'
        ],
        'contain' => [
            'Error',
            'Session',
            'Student'
        ]
    ];

    protected $tableButtons = [
        'Visualizar' => [
            'icon' => '<i class="far fa-eye"></i>',
            'url' => [
                'controller' => 'Markers',
                'action' => 'visualize',
                'plugin' => 'Colmena/ErrorsManager'
            ],
            'options' => [
                'class' => 'green-icon',
                'escape' => false
            ]
        ],
        'Borrar' => [
            'icon' => '<i class="fas fa-trash"></i>',
            'url' => [
                'controller' => 'Markers',
                'action' => 'delete',
                'plugin' => 'Colmena/ErrorsManager'
            ],
            'options' => [
                'confirm' => '¿Estás seguro de que quieres eliminar El marker?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ]
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
        $marker = $this->{$this->getName()}->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = $this->{$this->getName()}->Student->find('all')->where(['identifier' => $data['user_id']])->first();
            $error = $this->{$this->getName()}->Error->find('all')->where(['error_id' => $data['error_id']])->first();

            if (!isset($user)) {
                $user = $this->{$this->getName()}->Student->newEntity([
                    'identifier' => $data['user_id'],
                    'name' => '-',
                    'surname' => '-'
                ]);

                $user = $this->{$this->getName()}->Student->save($user);
            }

            $data['user_id'] = $user->id;
            $data['error_id'] = $error->id;

            $marker = $this->{$this->getName()}->patchEntity($marker, $data);
            $marker = $this->{$this->getName()}->save($marker);
            $marker = $this->linkRelations($marker);

            if (!isset($marker)) {
                $this->showErrors($marker);
            }

            $this->Flash->success('El marker se ha guardado correctamente.');
        }

        $this->set(compact('marker'));
        $this->set('_serialize', 'marker');
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function visualize($entityID = null, $locale = null)
    {
        $this->setLocale($locale);
        $entity = $this->{$this->getName()}->find('all')->where([$this->getName() . '.id' => $entityID])->contain([
            'Error',
            'Session',
            'Student'
        ])->first();

        $sessions = $this->{$this->getName()}->Session->find('list');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El marker se ha guardado correctamente.');
                return $this->redirect(['action' => 'visualize', $entity->id, $locale]);
            }

            $this->showErrors($entity);
        }

        $this->set('tabActions', $this->getTabActions('Markers', 'edit', $entity));
        $this->set(compact('entity', 'sessions'));
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
            $this->Flash->success('El marker se ha borrado correctamente.');
        } else {
            $this->Flash->error('El marker no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Function with links the marker with the corresponding session.
     * If there are more than one, it creates a conflict that will be
     * solved by the user
     *
     * @param [date] $creationTime
     * @param [type] $entity
     * @return void
     */
    private function linkRelations($marker)
    {
        $dateParts = explode(' ', $marker->creation_time);
        $date = date('Y-m-d', strtotime($dateParts[0])); // Y-m-d
        $hour = date('H:i:s', strtotime($dateParts[1])); // H:i:s

        $session = $this->{$this->getName()}->Session
            ->find('all')
            ->matching('SessionSchedules.PracticeGroups.Users', function ($q) use ($date, $hour, $marker) {
                return $q->where(['SessionSchedules.date' => $date, 'SessionSchedules.end_hour >=' => $hour, 'SessionSchedules.start_hour <=' => $hour, 'Users.id' => $marker->user_id]);
            })->first();

        $newEntity = [];
        if (isset($session)) {
            $newEntity['session_id'] = $session->id;
        }

        $marker = $this->{$this->getName()}->patchEntity($marker, $newEntity);
        $marker = $this->{$this->getName()}->save($marker);

        return $marker;
    }

    /**
     * Function which shows the entity error's on saving
     *
     * @param [Session] $entity
     * @return void
     */
    private function showErrors($entity)
    {
        $errorMsg = '<p>La sesión no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';

        foreach ($entity->getErrors() as $error) {
            $errorMsg .= '<p>' . $error['message'] . '</p>';
        }

        $this->Flash->error($errorMsg, ['escape' => false]);
    }

    
}
