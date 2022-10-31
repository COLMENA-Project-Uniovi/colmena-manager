<?php
namespace Colmena\UsersManager\Controller;

use Colmena\UsersManager\Controller\AppController;
use Cake\Event\Event;
use App\Encryption\EncryptTrait;
use Cake\Http\Exception\ForbiddenException;

class UserRolesController extends AppController
{
    use EncryptTrait;

    public $entityName = 'rol';
    public $entityNamePlural = 'roles';

    // Default pagination settings
    public $paginate = [
        'limit' => 10,
        'order' => [
            'User.id' => 'ASC'
        ],
    ];

    protected $tableButtons = [
        'Editar' => [
            'icon' => '<i class="fal fa-edit"></i>',
            'url' => [
                'controller' => 'Roles',
                'action' => 'edit',
                'plugin' => 'Colmena/UsersManager'
            ],
            'options' => [
                'class' => 'green-icon',
                'escape' => false
            ]
        ],
        'Borrar' => [
            'icon' => '<i class="fas fa-trash"></i>',
            'url' => [
                'controller' => 'Roles',
                'action' => 'delete',
                'plugin' => 'Colmena/UsersManager'
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar el rol?',
                'class' => 'red-icon',
                'escape' => false
            ]
        ]
    ];

    protected $header_actions = [
        'Añadir rol' => [
            'url' => [
                'controller' => 'UserRoles',
                'plugin' => 'Colmena/UsersManager',
                'action' => 'add'
            ]
        ],
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
		$this->Auth->allow([
            'login'
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
        $entity = $this->{$this->getName()}->newEmptyEntity();
        if ($this->request->is('post')) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());

            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('El rol se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id]);
            } else {
                $errorMsg = '<p>El rol no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
                foreach ($entity->errors() as $field => $error) {
                    $errorMsg .= '<p>' . $error['message'] . '</p>';
                }
                $this->Flash->error($errorMsg, ['escape' => false]);
            }
        }

        $this->set(compact('entity'));
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
                $this->Flash->success('El rol se ha guardado correctamente.');
                return $this->redirect(['action' => 'edit', $entity->id, $locale]);
            } else {
                $errorMsg = '<p>El rol no se ha guardado correctamente. Por favor, revisa los datos e inténtalo de nuevo.</p>';
                foreach ($entity->errors() as $field => $error) {
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
            $this->Flash->success('El rol se ha borrado correctamente.');
        } else {
            $this->Flash->error('El rol no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
