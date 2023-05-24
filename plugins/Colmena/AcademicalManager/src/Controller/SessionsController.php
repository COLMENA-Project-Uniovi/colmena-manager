<?php

namespace Colmena\AcademicalManager\Controller;

use Colmena\AcademicalManager\Controller\AppController;
use InvalidArgumentException;

class SessionsController extends AppController
{

  public $entityName = 'sesión';
  public $entityNamePlural = 'sesiones';

  // Default pagination settings
  public $paginate = [
    'limit' => 20,
    'order' => [
      'id' => 'DESC'
    ],
    'contain' => ['Languages']
  ];

  protected $tableButtons = [
    'Editar' => [
      'icon' => '<i class="fal fa-edit"></i>',
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
      'icon' => '<i class="far fa-trash-alt"></i>',
      'url' => [
        'controller' => 'Sessions',
        'action' => 'delete',
        'plugin' => 'Colmena/AcademicalManager'
      ],
      'options' => [
        'confirm' => '¿Estás seguro de que quieres eliminar la sesión?',
        'class' => 'red-icon',
        'escape' => false
      ]
    ],
    'Markers' => [
      'icon' => '<i class="far fa-bug"></i>',
      'url' => [
        'controller' => 'Sessions',
        'action' => 'session_markers',
        'plugin' => 'Colmena/AcademicalManager'
      ],
      'options' => [
        'escape' => false
      ]
    ],
    'Compilations' => [
      'icon' => '<i class="far fa-box"></i>',
      'url' => [
        'controller' => 'Sessions',
        'action' => 'session_compilations',
        'plugin' => 'Colmena/AcademicalManager'
      ],
      'options' => [
        'escape' => false,
        'class' => 'blue-icon',
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

  protected $tabActions = [];

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
    $this->set('tableButtons', $this->getTableButtons());
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
    $programmingLanguages = $this->{$this->getName()}->Languages->find('list')->order(['name' => 'ASC'])->toArray();

    if ($this->request->is('post')) {
      $data = $this->request->getData();
      $data['subject_id'] = $subjectID;
      $entity = $this->{$this->getName()}->patchEntity($entity, $data);

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('La sesión se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id, $subjectID]);
      }

      $this->showErrors($entity);
    }

    $this->set(compact('entity', 'subject', 'programmingLanguages'));
  }

  /**
   * Edit method
   *
   * @param string|null $id Product id.
   * @return void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Http\Exception\NotFoundException When record not found.
   */
  public function edit($entityID = null, $subjectID, $locale = null)
  {
    $this->setLocale($locale);
    $entity = $this->{$this->getName()}->get($entityID);
    $subject = $this->{$this->getName()}->Subjects->get($subjectID);
    $programmingLanguages = $this->{$this->getName()}->Languages->find('list')->order(['name' => 'ASC'])->toArray();

    if ($this->request->is(['patch', 'post', 'put'])) {
      $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('La sesión se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id, $subjectID, $locale]);
      }

      $this->showErrors($entity);
    }

    $this->set('tabActions', $this->getTabActions('Sessions', 'edit', $entity));
    $this->set(compact('entity', 'subject', 'programmingLanguages'));
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
   * Method which shows the markers associated to the current session
   *
   * @param [int] $sessionID
   * @param [int] $subjectID
   * @return void
   */
  public function sessionMarkers($sessionID, $subjectID)
  {
    $session = $this->{$this->getName()}->get($sessionID);
    $subject = $this->{$this->getName()}->Subjects->get($subjectID);

    $entities = $this->{$this->getName()}->Markers->find('all')->where(['session_id' => $sessionID])->contain(['Session', 'Student', 'Error'])->toArray();

    $this->set(compact('entities', 'session', 'subject'));
    $this->set('entities', $entities);
  }

  /**
   * Method which shows the compilations associated to the current session
   *
   * @param [int] $sessionID
   * @param [int] $subjectID
   * @return void
   */
  public function sessionCompilations($sessionID, $subjectID)
  {
    $session = $this->{$this->getName()}->get($sessionID);
    $subject = $this->{$this->getName()}->Subjects->get($subjectID);

    $entities = $this->{$this->getName()}->Compilations->find('all')->where(['session_id' => $sessionID])->contain(['Session', 'Student', 'Markers'])->toArray();

    $this->set(compact('entities', 'session', 'subject'));
    $this->set('entities', $entities);
  }

  /**
   * CRUD method which creates a new session
   *
   * @return a json response with the session created or an exception if the session could not be created
   */
  public function create()
  {
    $data = $this->request->getData();
    $entity = $this->{$this->getName()}->newEmptyEntity();

    $entity = $this->{$this->getName()}->patchEntity($entity, $data);

    try {
      $subject = $this->{$this->getName()}->save($entity);
    } catch (\Throwable $th) {
      throw new InvalidArgumentException("Entity could not be saved. Check the data and retry.");
    }

    $content = json_encode($subject);

    $this->response = $this->response->withStringBody($content);
    $this->response = $this->response->withType('json');

    return $this->response;
  }

  /**
   * CRUD method which edits a subject by its id
   *
   * @return a json response with the subject edited or an exception if the subject could not be edited
   */
  public function editSession()
  {
    $data = $this->request->getData();

    try {
      # We need to get the session to check if the session exists
      $entity = $this->{$this->getName()}->get($data['session_id']);
    } catch (\Throwable $th) {
      throw new InvalidArgumentException("Session does not exist.");
    }

    if (isset($data['project_id']) && !empty($data['project_id'])) {
      try {
        # We need to get the project to check if the project exists
        $this->{$this->getName()}->Projects->get($data['project_id']);
      } catch (\Throwable $th) {
        throw new InvalidArgumentException("Project does not exist.");
      }
    }

    if (isset($data['language_id']) && !empty($data['language_id'])) {
      try {
        # We need to check if the language exists
        $this->{$this->getName()}->Languages->get($data['language_id']);
      } catch (\Throwable $th) {
        throw new InvalidArgumentException("Academical year does not exist.");
      }
    }

    $entity = $this->{$this->getName()}->patchEntity($entity, $data);
    $subject = $this->{$this->getName()}->save($entity);

    $content = json_encode($subject);

    $this->response = $this->response->withStringBody($content);
    $this->response = $this->response->withType('json');

    return $this->response;
  }
}
