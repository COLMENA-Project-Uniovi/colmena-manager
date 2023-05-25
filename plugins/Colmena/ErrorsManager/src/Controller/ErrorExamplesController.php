<?php

namespace Colmena\ErrorsManager\Controller;

use Colmena\ErrorsManager\Controller\AppController;
use InvalidArgumentException;

class ErrorExamplesController extends AppController
{
  public $entityName = 'ejemplo de error';
  public $entityNamePlural = 'ejemplos de errores';

  // Default pagination settings
  public $paginate = [
    'limit' => 10,
    'order' => [
      'id' => 'ASC'
    ],
    'contain' => [
      'Users',
      'Errors',
      'Sessions'
    ]
  ];

  protected $tableButtons = [
    'Editar' => [
      'icon' => '<i class="fal fa-edit"></i>',
      'url' => [
        'controller' => 'ErrorExamples',
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
        'controller' => 'ErrorExamples',
        'action' => 'delete',
        'plugin' => 'Colmena/ErrorsManager'
      ],
      'options' => [
        'confirm' => '¿Estás seguro de que quieres eliminar el ejemplo de error?',
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
    $this->Auth->allow(['create', 'editErrorExample', 'list']);
  }

  /**
   * Index method
   *
   * @return void
   */
  public function index($errorID, $keyword = null)
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

    $entities = $this->{$this->getName()}->find('all')->contain(['Errors'])->where(['user_id' => $this->Auth->user('id'), $this->getName() . '.error_id' => $errorID])->toArray();

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
    $entity = $this->{$this->getName()}->newEmptyEntity();
    $sessions = $this->{$this->getName()}->Sessions->find('list');

    if ($this->request->is('post')) {
      $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('El ejemplo de error se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id]);
      }

      $this->showErrors($entity);
    }

    $this->set(compact('entity', 'sessions'));
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
    $sessions = $this->{$this->getName()}->Sessions->find('list');

    if ($this->request->is(['patch', 'post', 'put'])) {
      $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

      if ($this->{$this->getName()}->save($entity)) {
        $this->Flash->success('El ejemplo de error se ha guardado correctamente.');
        return $this->redirect(['action' => 'edit', $entity->id, $locale]);
      }

      $this->showErrors($entity);
    }

    $this->set('tabActions', $this->getTabActions('Users', 'edit', $entity));
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
      $this->Flash->success('El ejemplo de error se ha borrado correctamente.');
    } else {
      $this->Flash->error('El ejemplo de error no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
    }
    return $this->redirect(['action' => 'index']);
  }

  /**
   * Function which shows the entity error's on saving
   *
   * @param [ErrorExample] $entity
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
   * CRUD method which creates a new error example
   *
   * @return a json response with the error example created or an exception if the error example could not be created
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
   * CRUD method which edits an error example by its id
   *
   * @return a json response with the error example edited or an exception if the error example could not be edited
   */
  public function editErrorExample()
  {
    $data = $this->request->getData();

    try {
      # We need to get the error example to check if the error example exists
      $entity = $this->{$this->getName()}->get($data['example_id']);
    } catch (\Throwable $th) {
      throw new InvalidArgumentException("Error example does not exist.");
    }

    if (isset($data['session_id']) && !empty($data['session_id'])) {
      try {
        # We need to get the session to check if the session exists
        $this->{$this->getName()}->Sessions->get($data['session_id']);
      } catch (\Throwable $th) {
        throw new InvalidArgumentException("Session does not exist.");
      }
    }

    if (isset($data['error_id']) && !empty($data['error_id'])) {
      try {
        # We need to check if the error exists
        $this->{$this->getName()}->Errors->get($data['error_id']);
      } catch (\Throwable $th) {
        throw new InvalidArgumentException("Error does not exist.");
      }
    }

    if (isset($data['user_id']) && !empty($data['user_id'])) {
      try {
        # We need to check if the user exists
        $this->{$this->getName()}->Users->get($data['user_id']);
      } catch (\Throwable $th) {
        throw new InvalidArgumentException("User does not exist.");
      }
    }

    $entity = $this->{$this->getName()}->patchEntity($entity, $data);
    $subject = $this->{$this->getName()}->save($entity);

    $content = json_encode($subject);

    $this->response = $this->response->withStringBody($content);
    $this->response = $this->response->withType('json');

    return $this->response;
  }

  /**
   * CRUD method which edits an error example by its id
   *
   * @return a json response with the error example edited or an exception if the error example could not be edited
   */
  public function list()
  {
    $data = $this->request->getData();

    if (isset($data['session_id']) && !empty($data['session_id'])) {
      $sessionID = $this->{$this->getName()}->Sessions->get($data['session_id'])->id;

      $examples = $this->{$this->getName()}->find('all', [
        'conditions' => [
          'session_id' => $sessionID
        ]
      ])->toArray();
    } else if (isset($data['error_id']) && !empty($data['error_id'])) {
      $errorID = $this->{$this->getName()}->Errors->get($data['error_id'])->id;

      $examples = $this->{$this->getName()}->find('all', [
        'conditions' => [
          'error_id' => $errorID
        ]
      ])->toArray();
    } else if (isset($data['user_id']) && !empty($data['user_id'])) {
      $userID = $this->{$this->getName()}->Users->get($data['user_id'])->id;

      $examples = $this->{$this->getName()}->find('all', [
        'conditions' => [
          'user_id' => $userID
        ]
      ])->toArray();
    } else {
      $examples = $this->{$this->getName()}->find('all')->toArray();
    }

    $content = json_encode($examples);

    $this->response = $this->response->withStringBody($content);
    $this->response = $this->response->withType('json');

    return $this->response;
  }
}
