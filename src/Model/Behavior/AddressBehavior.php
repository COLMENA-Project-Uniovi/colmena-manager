<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\Utility\Inflector;
use Cake\I18n\I18n;
use Cake\Core\Configure;

/**
 * address behavior to add address capabilities to an entity.
 * This behavior automatically manages address fields in order to be saved or populated
 * automatically for each entity.
 */
class AddressBehavior extends Behavior
{
    /**
     * Table instance
     *
     * @var Table
     */
    protected $_table;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'referenceName' => '',
        'fields' => [
            'address',
            'city',
            'zip_code',
            'region',
            'country',
            'latitude',
            'longitude'
        ],
        'names' => [
            'default'
        ],
        'addressTable' => 'Addresses'
    ];

    /**
     * Constructor
     *
     * @param Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     */
    public function __construct(Table $table, array $config = [])
    {
        $config += [
            'referenceName' => $table->getRegistryAlias(),
        ];

        parent::__construct($table, $config);
    }

    /**
     * Initialize hook
     *
     * @param  array $config The config for this behavior.
     *
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->_addressTable = TableRegistry::getTableLocator()->get($this->_config['addressTable']);

        $addressAlias = $this->_addressTable->getAlias();
        $alias = $this->_table->getAlias();

        $this->_addressTable->addBehavior('Timestamp');

        /**
         * Association between Entity and address models
         */

        // Entity belongsTo address
        $this->_addressTable->belongsTo($alias, [
            'className' => $this->_config['referenceName'],
            'foreignKey' => 'foreign_key'
        ]);

        $this->_table->hasMany($addressAlias, [
            'className' => $addressAlias,
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => [
                $addressAlias . '.model' => $this->_config['referenceName']
            ]
        ]);
    }

    /**
     * Callback method that listens to the `beforeFind` event in the bound
     * table. It modifies the passed query by eager loading the address fields
     * and adding a formatter to set the address values properly.
     *
     * @param Event $event The beforeFind event that was fired.
     * @param Query $query Query
     * @param ArrayObject $options The options for the query
     *
     * @return Query
     */
    public function beforeFind(\Cake\Event\EventInterface $event, Query $query, $options)
    {
        // If we don't want to apply the before find actions use applyBehaviors option
        if (isset($options['applyBehaviors']) && !$options['applyBehaviors']) {
            return $query;
        }

        if (!isset($query->getContain()[$this->_addressTable->getAlias()])) {
            $query->contain([$this->_addressTable->getAlias()]);
        }

        // Check if select is empty
        if(!empty($query->clause("select"))) {
            $query->select([ $this->_table->getAlias() . '.id' ]);
        }

        $query
            ->formatResults(function ($results) {
                return $results->map(function ($row) {
                    if (!isset($row['addresses'])) {
                        return $row;
                    }

                    $address_aux = [];
                    foreach ($row['addresses'] as $address) {
                        foreach ($this->_config['fields'] as $field) {
                            $address_aux[$address['name']][$field] = $address[$field];
                        }
                    }

                    $row['addresses'] = $address_aux;

                    return $row;
                });
            });
    }

    /**
     * Modifies the structure of the data that will be passed to the patchEntity
     *
     * @param Event $event The beforeMarshal event that was fired.
     * @param ArrayObject $data
     * @param ArrayObject $options The options for the query
     *
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (!isset($data['addresses']) && empty($data['addresses'])) {
            $data['_addresses'] = [];
        } else {
            $data['_addresses'] = $data['addresses'];
            unset($data['addresses']);
        }
    }

    /**
     * Modifies the entity before it is saved so that address fields are persisted
     * in the database too.
     *
     * @param Event $event The beforeSave event that was fired
     * @param EntityInterface $entity The entity that is going to be saved
     * @param ArrayObject $options the options passed to the save method
     *
     * @return void
     */
    public function beforeSave(\Cake\Event\EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        // If we don't want to apply the before save actions use applyBehaviors option
        if (isset($options['applyBehaviors']) && !$options['applyBehaviors']) {
            return;
        }

        $default_locale = Configure::read('I18N.language');
        $locale = !empty($entity->get('_locale')) ? $entity->get('_locale') : $this->getLocale();

        if ($default_locale != $locale) {
            return;
        }

        if (!isset($entity['_addresses'])) {
            return;
        }

        // Get the locale of the entity we are saving if it is set
        $entity->setDirty('_addresses', false);
        $current_addresses = isset($entity['addresses'])? $entity['addresses']: [];
        $new_addresses = isset($entity['_addresses'])? $entity['_addresses']: [];

        $primaryKey = (array)$this->_table->getPrimaryKey();
        $key = $entity->get(current($primaryKey));
        $model = $this->_config['referenceName'];

        $modified = [];
        foreach ($current_addresses as $name => $current) {
            if (in_array($name, $this->_config['names'])) {
                $preexistent = $this->_addressTable->find()
                    ->where([
                        'name' => $name,
                        'locale' => $locale,
                        'foreign_key' => $key,
                        'model' => $model
                    ])
                    ->enableBufferedResults(false)
                    ->first();

                if (isset($new_addresses[$name])) {
                    $new_addresses[$name]['id'] = $preexistent['id'];
                }
            }
        }

        $addresses = [];
        foreach ($new_addresses as $name => $address) {
            $address['name'] = $name;
            $address['model'] = $model;
            $address['locale'] = $locale;

            array_push($addresses, new Entity($address, [
                'useSetters' => false,
                'markNew' => true
            ]));
        }

        $entity->set('addresses', array_values($addresses));
    }

    /**
     * Sets the locale that should be used for all future find and save operations on
     * the table where this behavior is attached to.
     *
     * @param string|null $locale The locale to use for fetching and saving records. Pass `null`
     * in order to unset the current locale, and to make the behavior fall back to using the
     * globally configured locale.
     *
     * @return $this
     */
    private function setLocale($locale = null)
    {
        $this->_locale = $locale != null ? $locale : I18n::getLocale();

        return $this;
    }

    /**
     * Returns the current locale.
     *
     * If no locale has been explicitly set via `setLocale()`, this method will return
     * the currently configured global locale.
     *
     * @return string
     */
    private function getLocale()
    {
        return isset($this->_locale) && $this->_locale ? $this->_locale : I18n::getLocale();
    }
}
