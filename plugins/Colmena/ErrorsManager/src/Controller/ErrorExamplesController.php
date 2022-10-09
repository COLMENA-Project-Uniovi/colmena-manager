<?php

namespace Colmena\ErrorsManager\Controller;

use Colmena\ErrorsManager\Controller\AppController;
use App\Encryption\EncryptTrait;

class ErrorExamplesController extends AppController
{
    use EncryptTrait;

    public $entityName = 'ejemplo de error';
    public $entityNamePlural = 'ejemplos de errores';

    // Default pagination settings
    public $paginate = [
        'limit' => 20,
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
            'icon' => '<i class="fas fa-edit"></i>',
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
            'icon' => '<i class="fas fa-trash-alt"></i>',
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

        $entities = $this->{$this->getName()}->find('all')
            ->matching('Users', function ($q) {
                return $q->where(
                    ['Users.id' => $this->Auth->user('id')]
                );
            })
            ->matching('Errors', function ($q) use ($errorID) {
                return $q->where(
                    [$this->getName() . '.error_id' => $errorID]
                );
            })->toArray();

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

        foreach ($entity->errors() as $error) {
            $errorMsg .= '<p>' . $error['message'] . '</p>';
        }

        $this->Flash->error($errorMsg, ['escape' => false]);
    }
}
