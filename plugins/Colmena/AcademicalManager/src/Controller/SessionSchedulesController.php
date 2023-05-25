<?php

namespace Colmena\AcademicalManager\Controller;

use Colmena\AcademicalManager\Controller\AppController;
use InvalidArgumentException;

class SessionSchedulesController extends AppController
{
  public $entityName = 'horario de sesión';
  public $entityNamePlural = 'horarios de sesión';

  // Default pagination settings
  public $paginate = [
    'limit' => 10,
    'order' => [
      'id' => 'DESC'
    ],
    'contain' => [
      'PracticeGroups', 'Sessions'
    ]
  ];

  protected $tableButtons = [
    'Editar' => [
      'icon' => '<i class="fal fa-edit"></i>',
      'url' => [
        'controller' => 'SessionSchedules',
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
        'controller' => 'SessionSchedules',
        'action' => 'delete',
        'plugin' => 'Colmena/AcademicalManager'
      ],
      'options' => [
        'confirm' => '¿Estás seguro de que quieres eliminar el horario?',
        'class' => 'red-icon',
        'escape' => false
      ]
    ]
  ];

  protected $header_actions = [
    'Añadir horario y grupo' => [
      'url' => [
        'controller' => 'SessionSchedules',
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
    $this->Auth->allow(['create', 'editSessionSchedule']);
  }

  /**
   * Index method
   *
   * @return void
   */
  public function index($sessionID, $subjectID)
  {
    $session = $this->{$this->getName()}->Sessions->get($sessionID);
    $subject = $this->{$this->getName()}->Sessions->Subjects->get($subjectID);

    $entities = $this->{$this->getName()}->find('all')->where(['session_id' => $sessionID]);

    // Paginator
    $settings = $this->paginate;

    //prepare the pagination
    $this->paginate = $settings;

    $entities = $this->paginate($entities);

    $this->set('header_actions', $this->getHeaderActions());
    $this->set('tableButtons', $this->getTableButtons());
    $this->set('entities', $entities);
    $this->set('session', $session);
    $this->set('subject', $subject);
  }

  /**
   * Add method
   *
   * @return void Redirects on successful add, renders view otherwise.
   */
  public function add($sessionID, $subjectID)
  {
    $entity = $this->{$this->getName()}->newEmptyEntity();
    $session = $this->{$this->getName()}->Sessions->get($sessionID);
    $subject = $this->{$this->getName()}->Sessions->Subjects->find('all')->where(['Subjects.id' => $subjectID])->contain(['AcademicalYear'])->first();
    $groups = $this->{$this->getName()}->PracticeGroups->find('list');

    if ($this->request->is('post')) {
      $data = $this->request->getData();
      $data['session_id'] = $sessionID;
      $entity = $this->{$this->getName()}->patchEntity($entity, $data);

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('El horario se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id]);
      }

      $this->showErrors(($entity));
    }

    $this->set(compact('entity', 'session', 'subject', 'groups'));
  }

  /**
   * Edit method
   *
   * @param string|null $entityID Product id.
   * @return void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Http\Exception\NotFoundException When record not found.
   */
  public function edit($entityID, $locale = null)
  {
    $this->setLocale($locale);
    $entity = $this->{$this->getName()}->get($entityID);
    $session = $this->{$this->getName()}->Sessions->get($entity->session_id);
    $subject = $this->{$this->getName()}->Sessions->Subjects->find('all')->where(['Subjects.id' => $session->subject_id])->contain(['AcademicalYear'])->first();
    $groups = $this->{$this->getName()}->PracticeGroups->find('list');

    if ($this->request->is(['patch', 'post', 'put'])) {
      $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('El horario se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id, $locale]);
      }

      $this->showErrors($entity);
    }

    $this->set('tabActions', $this->getTabActions('SessionsSchedules', 'edit', $entity));
    $this->set(compact('entity', 'subject', 'session', 'groups'));
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
      $this->Flash->success('El horario se ha borrado correctamente.');
    } else {
      $this->Flash->error('El horario no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
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
    $errorMsg = '<p>El horario no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';

    foreach ($entity->getErrors() as $error) {
      $errorMsg .= '<p>' . $error['message'] . '</p>';
    }

    $this->Flash->error($errorMsg, ['escape' => false]);
  }

  /**
   * CRUD method which creates a new session schedule
   *
   * @return a json response with the session schedule created or an exception if the session schedule could not be created
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
   * CRUD method which edits a session schedule by its id
   *
   * @return a json response with the session schedule edited or an exception if the session schedule could not be edited
   */
  public function editSessionSchedule()
  {
    $data = $this->request->getData();

    try {
      # We need to get the session schedule to check if the session schedule exists
      $entity = $this->{$this->getName()}->get($data['schedule_id']);
    } catch (\Throwable $th) {
      throw new InvalidArgumentException("Session schedule does not exist.");
    }

    if (isset($data['session_id']) && !empty($data['session_id'])) {
      try {
        # We need to get the session to check if the session exists
        $this->{$this->getName()}->Sessions->get($data['session_id']);
      } catch (\Throwable $th) {
        throw new InvalidArgumentException("Session does not exist.");
      }
    }

    if (isset($data['practice_group_id']) && !empty($data['practice_group_id'])) {
      try {
        # We need to check if the practice group exists
        $this->{$this->getName()}->PracticeGroups->get($data['practice_group_id']);
      } catch (\Throwable $th) {
        throw new InvalidArgumentException("Practice group does not exist.");
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
