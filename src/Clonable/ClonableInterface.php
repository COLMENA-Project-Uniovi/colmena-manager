<?php

namespace App\Clonable;

/**
 * Clonable interface to clone or duplicate entities.
 * Each entity we want to clone or duplicate in other language mush implement
 * this interface.
 */
interface ClonableInterface
{
    /**
     * Clone an entity. The $options variable is fully configurable and
     * can be used inside the implementation to make a proper clone of the entity.
     *
     * @param  integer $id      the entity ID
     * @param  array   $options the options to clone the entity
     *
     * @return boolean whether the entity was cloned successfully or not
     */
    public function duplicate($id, $options = []);

    /**
     * Duplicate all the entities in a new language. The $options variable is fully
     * configurable and can be used inside the implementation to make a proper
     * duplication of the entity or to narrow the duplication to a certain set
     * of entities.
     *
     * @param  string $locale  the new locale to clone the entities
     * @param  array  $options the options to duplicate the entity
     *
     * @return boolean whether the entity was duplicated successfully or not
     */
    public function duplicateAll($locale, $options = []);
}
