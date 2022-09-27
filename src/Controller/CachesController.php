<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use App\Utility\CacheUtility;

/**
 * Caches Controller
 */
class CachesController extends AppController
{
    public $entityName = 'cache';

    protected $tabActions = [];

    public $header_actions = [
        'Activar cache' => [
            'url' => [
                'controller' => 'Caches',
                'plugin' => false,
                'action' => 'activate'
            ]
        ],
        'Desactivar cache' => [
            'url' => [
                'controller' => 'Caches',
                'plugin' => false,
                'action' => 'deactivate'
            ]
        ],
        'Generar cache completa' => [
            'url' => [
                'controller' => 'Caches',
                'plugin' => false,
                'action' => 'generateAll'
            ]
            ],
        'Borrar cache completa' => [
            'url' => [
                'controller' => 'Caches',
                'plugin' => false,
                'action' => 'clearAll'
            ]
        ]
    ];

    protected $table_buttons = [
        'Generar' => [
            'url' => [
                'controller' => 'Caches',
                'action' => 'generate',
                'plugin' => false
            ],
            'options' => [
                'class' => 'button'
            ]
        ],
        'Borrar' => [
            'url' => [
                'controller' => 'Caches',
                'action' => 'delete',
                'plugin' => false
            ],
            'options' => [
                'confirm' => '¿Está seguro de que desea eliminar la caché de está página?',
                'class' => 'button'
            ]
        ]
    ];

    private $folder_conditions = [
        'type' => 'general',
        'field' => 'folder'
    ];

    /**
     * Method to get all SEO folders
     *
     * @return folders
     */
    private function getFolders($conditions = []) {
        $conditions = array_merge($this->folder_conditions, $conditions);

        $seo_table = TableRegistry::getTableLocator()->get('seo');

        $folders = $seo_table->find('all')->where($conditions)->toArray();

        $folders = array_map(function($folder) {
            $this->setLocale($folder['locale']);
            $entity_table = TableRegistry::getTableLocator()->get($folder->model);
            $entity = $entity_table->get($folder->foreign_key);
            if($entity) {
                $entity = $entity_table->formatSeo($entity, true);
                $folder->url = substr($entity['seo']['folder'], 0, -1);
                $folder->name = $entity[$entity_table->getDisplayField()];
                $folder->locale = $folder['locale'];
            }

            return $folder;
        }, $folders);

        return $folders;
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($keyword = null)
    {
        if ($this->request->is('post')) {
            //recover the keyword
            $keyword = $this->request->getData('keyword');
            //re-send to the same controller with the keyword
            return $this->redirect(['action' => 'index', $keyword]);
        }

        $config_table = TableRegistry::getTableLocator()->get('Configs');
        $config = $config_table->find('all')->where(['name' => 'cache'])->first();

        $this->tabActions = Configure::read('Config.tabActions');

        $conditions = [];

        if(!is_null($keyword) && !empty($keyword)) {
            $conditions['OR'] = [
                'model LIKE' => '%'.$keyword.'%',
                'content LIKE' => '%'.$keyword.'%',
                'locale LIKE' => '%'.$keyword.'%',
            ];
        }

        $folders = $this->getFolders($conditions);

        $this->set('header_actions', $this->getHeaderActions($config));
        $this->set('tabActions', $this->getTabActions('Caches', 'index', null));
        $this->set('table_buttons', $this->getTableButtons());
        $this->set('keyword', $keyword);
        $this->set(compact('config', 'folders'));
    }

    /**
     * Method to activate the cache
     *
     * @return redirect to the referer page
     */
    public function activate()
    {
        $config_table = TableRegistry::getTableLocator()->get('Configs');
        $config = $config_table->find('all')->where(['name' => 'cache'])->first();

        $config->value = 'true';
        if ($config_table->save($config)) {
            $this->Flash->success('La cache se ha activado correctamente.');
        }
        return $this->redirect($this->referer());
    }

    /**
     * Method to deactivate the cache
     *
     * @return redirect to the referer page
     */
    public function deactivate()
    {
        $config_table = TableRegistry::getTableLocator()->get('Configs');
        $config = $config_table->find('all')->where(['name' => 'cache'])->first();

        $config->value = 'false';
        if ($config_table->save($config)) {
            CacheUtility::clear();
            $this->Flash->success('La cache se ha desactivado correctamente.');
        }

        return $this->redirect($this->referer());
    }

    /**
     * Method to generate one page cache 
     *
     * @return redirect to the referer page
     */
    private function generateOnePageCache($model, $url, $locale = 'es_ES')
    {
        $this->setLocale($locale);

        $entity_table = TableRegistry::getTableLocator()->get($model);
        $entity = $entity_table->getByUrl($url);

        if ($entity) {
            CacheUtility::set($url . '.' . $locale, $entity->toArray(), 'long');
        }
    }

    /**
     * Get request to generate one page cache
     *
     * @return redirect to the referer page
     */
    public function generate()
    {
        $model = $this->request->getQuery('model');
        $url = $this->request->getQuery('url');
        $locale = $this->request->getQuery('locale');
        
        $this->generateOnePageCache($model, $url, $locale);

        $this->Flash->success('La cache se de la página se ha generado correctamente.');
        return $this->redirect($this->referer());
    }

    /**
     * Method to generate all the cache
     *
     * @return redirect to the referer page
     */
    public function generateAll()
    {
        foreach($this->getFolders() as $folder) {
            $this->generateOnePageCache($folder['model'], $folder['url'], $folder['locale']);
        }

        $this->Flash->success('La cache de todas las páginas se ha generado correctamente.');
        return $this->redirect($this->referer());
    }

    /**
     * Method to delete the cache of one page
     *
     * @return redirect to the referer page
     */
    public function delete()
    {
        $url = $this->request->getQuery('url');
        $locale = $this->request->getQuery('locale');

        CacheUtility::delete($url . '.' . $locale, 'long');

        $this->Flash->success('La cache se de la página se ha eliminado correctamente.');
        return $this->redirect($this->referer());
    }

    /**
     * Method to delete all the cache
     *
     * @return redirect to the referer page
     */
    public function clearAll()
    {
        CacheUtility::clear();

        $this->Flash->success('La cache se ha vaciado correctamente.');
        return $this->redirect($this->referer());
    }

    /**
     * Get the configuration for the buttons in the header of the desired view
     *
     * @return array with the config of the buttons
     */
    protected function getHeaderActions($entity = null)
    {
        if ($entity != null) {
            $aux_actions = [];
            foreach ($this->header_actions as $name => $config) {
                $aux_config = $config;
                if ($entity->value == 'true' && $config['url']['action'] == 'activate') {
                    continue;
                }
                if ($entity->value == 'false' && $config['url']['action'] == 'deactivate') {
                    continue;
                }

                $aux_actions[$name] = $aux_config;
            }
            $this->header_actions = $aux_actions;
        }

        return $this->Roles->composeUserOptions($this->header_actions);
    }
}
