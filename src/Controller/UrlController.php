<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

use App\Utility\CacheUtility;

/**
 * Url Controller
 *
 */
class UrlController extends AppController
{
    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event The beforeFilter event.
     *
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(
            [
                'translate'
            ]
        );
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($url, $locale)
    {
        if ($url == '' || !in_array($locale, array_keys(Configure::read('I18N.locales')))) {
            throw new BadRequestException('Error Processing Request');
        }

        //check if url is in cache for given locale
        $entity = CacheUtility::get($url . '.' . $locale, 'long');
        if ($entity === false) {
            $entity = false;
            foreach (Configure::read('API.searchable_entities') as $entity_name) {
                $this->setLocale($locale);
                
                $entity_table = TableRegistry::getTableLocator()->get($entity_name);
                $entity = $entity_table->getByUrl($url);

                if ($entity) {
                    //put entity in cache when found
                    CacheUtility::set($url . '.' . $locale, $entity->toArray(), 'long');
                    break;
                }
            }

            if (!$entity) {
                throw new NotFoundException('Url no encontrada');
            }
        }

        $this->set('entity', $entity);
        $this->set('_serialize', 'entity');
    }

    /**
     * Translate method
     *
     * @return void
     */
    public function translate($url, $locale, $new_locale)
    {
        if ($url == '' ||
            !in_array($locale, array_keys(Configure::read('I18N.locales'))) ||
            !in_array($new_locale, array_keys(Configure::read('I18N.locales')))) {
            throw new BadRequestException('Error Processing Request');
        }

        $entity = false;
        foreach (Configure::read('API.searchable_entities') as $entity_name) {
            $this->setLocale($locale);
            $entity_table = TableRegistry::getTableLocator()->get($entity_name);
            $entity = $entity_table->getByUrl($url);

            if ($entity) {
                break;
            }
        }

        if (!$entity) {
            throw new NotFoundException('Url no encontrada');
        }
        $this->setLocale($new_locale);
        $new_entity = $entity_table->get($entity->id);

        $new_entity = $entity_table->formatSeo($new_entity, true);

        $this->set('entity', $new_entity);
        $this->set('_serialize', 'entity');
    }
}
