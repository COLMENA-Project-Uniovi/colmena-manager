<?php

namespace Colmena\AcademicalManager\Controller;

use Colmena\AcademicalManager\Controller\AppController;
use App\Encryption\EncryptTrait;
use Cake\ORM\TableRegistry;

class SubjectsController extends AppController
{
  use EncryptTrait;

  public $entityName = 'asignatura';
  public $entityNamePlural = 'asignaturas';

  // Default pagination settings
  public $paginate = [
    'limit' => 10,
    'order' => [
      'id' => 'DESC'
    ],
    'contain' => [
      'AcademicalYear',
      'Projects'
    ]
  ];

  protected $tableButtons = [
    'Editar' => [
      'icon' => '<i class="fal fa-edit"></i>',
      'url' => [
        'controller' => 'Subjects',
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
        'controller' => 'Subjects',
        'action' => 'delete',
        'plugin' => 'Colmena/AcademicalManager'
      ],
      'options' => [
        'confirm' => '¿Estás seguro de que quieres eliminar la asignatura?',
        'class' => 'red-icon',
        'escape' => false
      ]
    ],
    'Sesiones' => [
      'icon' => '<i class="fal fa-calendar-alt"></i>',
      'url' => [
        'controller' => 'Sessions',
        'action' => 'index',
        'plugin' => 'Colmena/AcademicalManager',
      ],
      'options' => [
        'escape' => false,
        'class' => 'gray-icon'
      ]
    ]
  ];

  protected $header_actions = [
    'Añadir asignatura' => [
      'url' => [
        'controller' => 'Subjects',
        'plugin' => 'Colmena/AcademicalManager',
        'action' => 'add'
      ]
    ]
  ];

  /**
   * Before filter
   *
   * @param \Cake\Event\Event $event The beforeFilter event.
   *
   */
  public function beforeFilter(\Cake\Event\EventInterface $event)
  {
    parent::beforeFilter($event);
    $this->Auth->allow(['list', 'listSubjectById', 'getSession']);
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

    $entities = $this->{$this->getName()}->find('all')->where([$this->getName() . '.project_id' => $projectID]);
    $entities = $this->paginate($entities);

    $this->set('header_actions', $this->getHeaderActions());
    $this->set('tableButtons', $this->getTableButtons());
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
    $projectID = $this->getSessionProject();
    $project = $this->{$this->getName()}->Projects->find('all')->where(["id" => $projectID])->first();
    $entity = $this->{$this->getName()}->newEmptyEntity();
    $academicalYears = $this->{$this->getName()}->AcademicalYear->find('list')->toArray();

    if ($this->request->is('post')) {
      $data = $this->request->getData();
      $entity['project_id'] = $projectID;

      $entity = $this->{$this->getName()}->patchEntity($entity, $data);

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('La asignatura se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id]);
      }

      $this->showErrors($entity);
    }

    $this->set(compact('entity', 'project', 'academicalYears'));
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

    $entity = $this->{$this->getName()}->find('all')->where([$this->getName() . '.id' => $entityID])->contain(['AcademicalYear', 'Projects'])->first();

    $academicalYears = $this->{$this->getName()}->AcademicalYear->find('list')->all();

    if ($this->request->is(['patch', 'post', 'put'])) {
      $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('La asignatura se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id, $locale]);
      }

      $this->showErrors($entity);
    }

    $this->set('tabActions', $this->getTabActions('Subjects', 'edit', $entity));
    $this->set(compact('entity', 'academicalYears'));
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
      $this->Flash->success('La asignatura se ha borrado correctamente.');
    } else {
      $this->Flash->error('La asignatura no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
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
      $query->matching('Projects', function ($q) use ($projectID) {
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
    $query = $this->{$this->getName()}->find('all')
      ->where(['id' => $subjectID])
      ->contain(
        [
          'Sessions' => function ($q) {
            return $q->contain([
              'SessionSchedules' => function ($q) {
                return $q->contain([
                  'PracticeGroups' => function ($q) {
                    return $q->contain([
                      'Supervisor'
                    ]);
                  },
                ]);
              },
            ]);
          },
        ]
      )
      ->first();

    $this->response = $this->response->withStringBody($query);
    $this->response = $this->response->withType('json');

    return $this->response;
  }


  // Some helpfull functions
  public function getSession()
  {
    $sessionID = $this->request->getData('id');
    $query = $this->{$this->getName()}->Sessions->find('all')
      ->where(['id' => $sessionID])
      ->contain(
        [
          'SessionSchedules'
        ]
      )
      ->first();

    $languages_table = TableRegistry::getTableLocator()->get('em_languages');
    $query['language'] = $languages_table->get($query['language_id']);

    $markersTable = TableRegistry::getTableLocator()->get('em_markers');
    $query['markers'] = $markersTable->find('all')->where(['session_id' => $sessionID])->toList();

    $errorsTable = TableRegistry::getTableLocator()->get('em_errors');
    $familyErrorsTable = TableRegistry::getTableLocator()->get('em_family_errors');

    foreach ($query['markers'] as $marker) {
      $marker['error'] = $errorsTable->get($marker['error_id']);
      if ($marker['error'] && $marker['error']['family_id']) {
        $marker['error']['family'] = $familyErrorsTable->get($marker['error']['family_id']);
      }
    }

    $compilationsTable = TableRegistry::getTableLocator()->get('em_compilations');
    $query['compilations'] = $compilationsTable->find('all')->where(['session_id' => $sessionID])->toList();

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

  /**
   * Function which shows the entity error's on saving
   *
   * @param [Subject] $entity
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
