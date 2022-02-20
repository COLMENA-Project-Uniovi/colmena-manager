<?php

namespace App\Model\Behavior;

use ArrayObject;
use Cake\ORM\Behavior\TranslateBehavior;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Core\Configure;

/**
 * Seo behavior to add SEO capabilities to an entity.
 * This behavior automatically manages SEO fields in order to be saved or populated
 * automatically for each entity.
 */
class I18nBehavior extends TranslateBehavior
{
    /**
     * Initialize hook
     *
     * @param array $config The config for this behavior.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function beforeSave(\Cake\Event\EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        $default_locale = Configure::read('I18N.language');
        $locale = !empty($entity->get('_locale')) ? $entity->get('_locale') : $this->getLocale();

        if ($default_locale == $locale) {
            return;
        }

        foreach ($this->_config['fields'] as $key => $value) {
            if (isset($entity[$value])) {
                $entity->setDirty($value, true);
            }
        }
        return parent::beforeSave($event, $entity, $options);
    }
}
