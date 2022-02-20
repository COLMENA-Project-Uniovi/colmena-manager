<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use App\Utility\CacheUtility;
use Cake\Core\Configure;

/**
 * Notifications Controller
 */
class NotificationsController extends AppController
{
    public $entity_name = 'notificación';
    public $entity_name_plural = 'notificaciones';

    protected $tab_actions = [];

    public $header_actions = [
        'Añadir Notificación' => [
            'url' => [
                'controller' => 'Notifications',
                'plugin' => false,
                'action' => 'add'
            ]
        ],
    ];

    public $table_buttons = [
        'Editar' => [
            'url' => [
                'controller' => 'Notifications',
                'action' => 'edit',
                'plugin' => false
            ],
            'options' => [
                'class' => 'button'
            ]
        ],
        'Borrar' => [
            'url' => [
                'controller' => 'Notifications',
                'action' => 'delete',
                'plugin' => false
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar la notificación?',
                'class' => 'button'
            ]
        ]
    ];


    /**
     * Index method
     *
     * @param  string $keyword keyword for search
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
                    $this->getName() . '.model LIKE' => '%' . $keyword . '%',
                    $this->getName() . '.action LIKE' => '%' . $keyword . '%'
                ]
            ];
        }

        //prepare the pagination
        $this->paginate = $settings;

        $entities = $this->paginate($this->modelClass);

        $this->tab_actions = Configure::read('Config.tab_actions');

        $this->set('entities', $entities);
        $this->set('_serialize', 'entities');
        $this->set('keyword', $keyword);
        $this->set('header_actions', $this->getHeaderActions());
        $this->set('table_buttons', $this->getTableButtons());
        $this->set('tab_actions', $this->getTabActions('Notifications', 'index', null));
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
                $this->Flash->success('La notificación se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('La notificación no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }

        $this->set('plugins', $this->getPlugins());
        $this->set(compact('entity'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Notification id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $entity = $this->{$this->getName()}->get($id);
        if (!isset($entity) || is_null($entity)) {
            $this->Flash->error('Notificación no encontrada');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());
            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('La notificación se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('La notificación no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }

        $this->set('plugins', $this->getPlugins());
        $this->set(compact('entity'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Notification id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->{$this->getName()}->get($id);
        if ($this->{$this->getName()}->delete($entity)) {
            $this->Flash->success('La notificación se ha borrado correctamente.');
        } else {
            $this->Flash->error('La notificación no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Return all plugins in neo admin 3x
     * 
     * @return array name of the plugins
     */
    public function getPlugins() 
    {
        $plugins = parent::getPlugins();
        $formatted_plugins = [];

        foreach($plugins as $plugin) {
            $formatted_plugins[$plugin] = $plugin;
        }

        return $formatted_plugins;
    }
}
