<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Http\Exception\InternalErrorException;

class BooleableBehavior extends Behavior
{
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
     * Change boolean property
     *
     * @param  Entity $entity     to change the boolean value
     * @param         $field_name to be changed
     *
     * @return void
     */
    public function changeBoolean($entity, $field_name = null)
    {
        // Clean the entire property as long as we only want to modify one field
        // This avoids the saving of properties that have been modified by the
        // beforeFind methods
        $entity->clean();

        $value = $entity->get($field_name);

        $entity->set($field_name, !$value);

        if (!$this->_table->save($entity, ['applyBehaviors' => false])) {
            throw new InternalErrorException('Ha ocurrido un error interno al cambiar la propiedad.');
        }
        return true;
    }
}
