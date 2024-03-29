<?php

namespace Colmena\AcademicalManager\Controller;

use Colmena\AcademicalManager\Controller\AppController;
use App\Encryption\EncryptTrait;

class AcademicalYearsController extends AppController
{
    use EncryptTrait;

    public $entityName = 'curso academico';
    public $entityNamePlural = 'curso academicos';

    // Default pagination settings
    public $paginate = [
        'limit' => 10,
        'order' => [
            'id' => 'DESC'
        ]
    ];

    protected $tableButtons = [
        'Editar' => [
            'icon' => '<i class="fal fa-edit"></i>',
            'url' => [
                'controller' => 'AcademicalYears',
                'action' => 'edit',
                'plugin' => 'Colmena/AcademicalManager'
            ],
            'options' => [
                'class' => 'green-icon',
                'escape' => false
            ]
        ],

        'Borrar' => [
            'icon' => '<i class="fal fa-trash-alt"></i>',
            'url' => [
                'controller' => 'AcademicalYears',
                'action' => 'delete',
                'plugin' => 'Colmena/AcademicalManager'
            ],
            'options' => [
                'confirm' => '¿Estás seguro de que quieres eliminar la curso academico?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ]
    ];

    protected $header_actions = [
        'Añadir curso académico' => [
            'url' => [
                'controller' => 'AcademicalYears',
                'plugin' => 'Colmena/AcademicalManager',
                'action' => 'add'
            ]
        ]
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
        $this->Auth->allow(['list', 'listSubjectById']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($keyword = null)
    {
        $projectID = $this->getSessionProject();

        if ($this->request->is('post')) {
            //recover the keyword
            $keyword = $this->request->getData('keyword');
            //re-send to the same controller with the keyword
            return $this->redirect(['action' => 'index', $keyword]);
        }

        // Add the project id to the pagination
        $this->paginate['where'] = [
            'project_id' => $projectID
        ];

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
        $filteredEntities = array();

        foreach ($entities as $entity) {
            if ($entity['project_id'] == $projectID) {
                array_push($filteredEntities, $entity);
            }
        }

        $this->set('header_actions', $this->getHeaderActions());
        $this->set('tableButtons', $this->getTableButtons());
        $this->set('entities', $filteredEntities);
        $this->set('keyword', $keyword);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $project = $this->getSessionProject();
        $entity = $this->{$this->getName()}->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['project_id'] = $project;

            $entity = $this->{$this->getName()}->patchEntity($entity, $data);

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('la curso academico se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id]);
            } else {
                $errorMsg = '<p>La curso academico no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
                foreach ($entity->getErrors() as $field => $error) {
                    $errorMsg .= '<p>' . $error['message'] . '</p>';
                }
                $this->Flash->error($errorMsg, ['escape' => false]);
            }
        }

        $this->set(compact('entity', 'project'));
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
                $this->Flash->success('la curso academico se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id, $locale]);
            } else {
                $errorMsg = '<p>La curso academico no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
                foreach ($entity->getErrors() as $field => $error) {
                    $errorMsg .= '<p>' . $error['message'] . '</p>';
                }
                $this->Flash->error($errorMsg, ['escape' => false]);
            }
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
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->{$this->getName()}->get($id);
        if ($this->{$this->getName()}->delete($entity)) {
            $this->Flash->success('la curso academico se ha borrado correctamente.');
        } else {
            $this->Flash->error('la curso academico no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    private function getSessionProject()
    {
        $session = $this->request->getSession();
        $projectID = $session->read('Projectid');

        return $projectID;
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
