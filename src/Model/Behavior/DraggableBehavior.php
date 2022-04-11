<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Http\Exception\InternalErrorException;

class DraggableBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fieldName' => 'sort',
        'allowedSortFields' => [
            'sort',
            'sort_mobile'
        ]
    ];

    /**
     * Initialize behavior with config if needed
     *
     * @param  array  $config configuration of the behavior
     *
     * @return void
     */
    public function initialize(array $config): void
    {
        // Some initialization code here
    }

    /**
     * Change sort property
     *
     * @param  Entity  $entity     to change the sort value
     * @param  integer $sort       the new sort of the entity
     *
     * @return void
     */
    public function changeSort($entity, $sort, $sort_field = null)
    {
        if ($sort_field != null) {
            if (!in_array($sort_field, $this->_config['allowedSortFields'])) {
                return false;
            }
            $this->_config['fieldName'] = $sort_field;
        }
        // Clean the entire property as long as we only want to modify one field
        // This avoids the saving of properties that have been modified by the
        // beforeFind methods
        $entity->clean();
        $entity->set($this->_config['fieldName'], $sort);

        if (!$this->_table->save(
            $entity,
            [
                'applyBehaviors' => false
            ]
        )) {
            throw new InternalErrorException('Ha ocurrido un error interno al cambiar la ordenación.');
        }
        return true;
    }
}
