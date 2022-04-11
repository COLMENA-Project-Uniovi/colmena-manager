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
 * parameters behavior to add parameters capabilities to an entity.
 * This behavior automatically manages parameters fields in order to be saved or populated
 * automatically for each entity.
 */
class ParametersBehavior extends Behavior
{
    /**
     * Table instance
     *
     * @var \Cake\ORM\Table
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
            'parameters',
        ],
        'parametersTable' => 'Parameters'
    ];

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
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
        $this->_parametersTable = TableRegistry::getTableLocator()->get($this->_config['parametersTable']);

        $parametersAlias = $this->_parametersTable->getAlias();
        $alias = $this->_table->getAlias();

        $this->_parametersTable->addBehavior('Timestamp');

        /**
         * Association between Entity and parameters models
         */

        // Entity belongsTo parameters
        $this->_parametersTable->belongsTo($alias, [
            'className' => $this->_config['referenceName'],
            'foreignKey' => 'foreign_key'
        ]);

        $this->_table->hasOne($parametersAlias, [
            'className' => $parametersAlias,
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => [
                $parametersAlias . '.model' => $this->_config['referenceName']
            ]
        ]);
    }

    /**
     * Callback method that listens to the `beforeFind` event in the bound
     * table. It modifies the passed query by eager loading the parameters fields
     * and adding a formatter to set the parameters values properly.
     *
     * @param  \Cake\Event\Event $event The beforeFind event that was fired.
     * @param  \Cake\ORM\Query $query Query
     * @param  \ArrayObject $options The options for the query
     *
     * @return void
     */
    public function beforeFind(\Cake\Event\EventInterface $event, Query $query, $options)
    {
        // If we don't want to apply the before find actions use applyBehaviors option
        if (isset($options['applyBehaviors']) && !$options['applyBehaviors']) {
            return $query;
        }

        if (!isset($query->getContain()[$this->_parametersTable->getAlias()])) {
            $query->contain([$this->_parametersTable->getAlias()]);
        }

        // Check if select is empty
        if(!empty($query->clause("select"))) {
            $query->select([ $this->_table->getAlias() . '.id' ]);
        }
        
        return $query;
    }

    /**
     * Modifies the structure of the data that will be passed to the patchEntity
     *
     * @param  \Cake\Event\Event $event   The beforeMarshal event that was fired.
     * @param  \ArrayObject      $options The data of the entity
     * @param  \ArrayObject      $options The options for the query
     *
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (!isset($data['parameter']) && empty($data['parameter'])) {
            $data['_parameter'] = [];
        } else {
            $data['_parameter'] = $data['parameter'];
            unset($data['parameter']);
        }
    }

    /**
     * Modifies the entity before it is saved so that address fields are persisted
     * in the database too.
     *
     * @param  \Cake\Event\Event $event The beforeSave event that was fired
     * @param  \Cake\Datasource\EntityInterface $entity The entity that is going to be saved
     * @param  \ArrayObject $options the options passed to the save method
     *
     * @return void
     */
    public function beforeSave(\Cake\Event\EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        
        // If we don't want to apply the before save actions use applyBehaviors option
        if (isset($options['applyBehaviors']) && !$options['applyBehaviors']) {
            return;
        }

        if (!isset($entity['_parameter'])) {
            return;
        }

        $entity->setDirty('_parameter', false);
        $current_parameter = isset($entity['parameter'])? $entity['parameter']: [];
        $new_parameter = isset($entity['_parameter'])? $entity['_parameter']: [];

        $primaryKey = (array)$this->_table->getPrimaryKey();
        $key = $entity->get(current($primaryKey));

        if($key) {
            $model = $this->_config['referenceName'];

            $preexistent = $this->_parametersTable->find()
                ->where([
                    'foreign_key' => $key,
                    'model' => $model
                ])
                ->enableBufferedResults(false)
                ->first();

            if (isset($preexistent)) {
                $new_parameter['id'] = $preexistent['id'];
            }

            $new_parameter['model'] = $model;

            $parameter = new Entity($new_parameter, [
                'useSetters' => false,
                'markNew' => true
            ]);

            $entity->set('parameter', $parameter);
        }
    }
}
