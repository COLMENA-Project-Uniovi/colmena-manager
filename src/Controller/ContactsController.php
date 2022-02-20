<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Contacts Controller
 *
 * @property \App\Model\Table\CompaniesTable $Contacts
 */
class ContactsController extends AppController
{
    public $entity_name = 'dirección';
    public $entity_name_plural = 'direcciones';

    public $button_options = [
        'Editar' => [
            'url' => [
                'controller' => 'Contacts',
                'action' => 'edit',
                'plugin' => false
            ],
            'options' => [
                'class' => 'button'
            ]
        ],
        'Borrar' => [
            'url' => [
                'controller' => 'Contacts',
                'action' => 'delete',
                'plugin' => false
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar la dirección?',
                'class' => 'button'
            ]
        ]
    ];

    public $header_actions = [
        'Añadir dirección' => [
            'url' => [
                'controller' => 'Contacts',
                'plugin' => false,
                'action' => 'add'
            ]
        ]
    ];

    // Default pagination settings
    public $paginate = [
        'limit' => 20,
        'order' => [
            'Contacts.id' => 'ASC'
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
            return $this->redirect(['action' => 'index/' . $keyword]);
        }

        // Paginator
        $settings = $this->paginate;

        // If performing search, there is a keyword
        if ($keyword != null) {
            // Change pagination conditions for searching
            $settings['conditions'] = [
                'OR' => [
                    $this->getName() . '.title LIKE' => '%' . $keyword . '%'
                ]
            ];
        }

        //prepare the pagination
        $this->paginate = $settings;

        $entities = $this->paginate($this->modelClass);

        $this->set('header_actions', $this->getHeaderActions());
        $this->set('table_buttons', $this->getTableButtons());
        $this->set(compact('entities'));
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
                $this->Flash->success('La dirección se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('La dirección no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }
        $this->set(compact('entity'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Company id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $entity = $this->{$this->getName()}->get($id);
        if (!isset($entity) || is_null($entity)) {
            $this->Flash->error('Dirección no encontrada');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->{$this->getName()}->patchEntity($entity, $this->request->getData());
            if ($this->{$this->getName()}->save($entity)) {
                $this->Flash->success('La dirección se ha guardado correctamente.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('La dirección no se ha guardado correctamente. Por favor, inténtalo de nuevo más tarde.');
            }
        }

        $this->set(compact('entity'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Company id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entity = $this->{$this->getName()}->get($id);
        if ($this->{$this->getName()}->delete($entity)) {
            $this->Flash->success('La dirección se ha borrado correctamente.');
        } else {
            $this->Flash->error('La dirección no se ha borrado correctamente. Por favor, inténtalo de nuevo más tarde.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
