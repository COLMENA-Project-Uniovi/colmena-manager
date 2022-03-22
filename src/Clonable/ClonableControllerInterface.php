<?php

namespace App\Clonable;

/**
 * Clonable interface for controller to duplicate entities.
 * Each controller of entities that implements the App\Clonable\ClonableInterface
 * mush implement this class to provide a method to duplicate an object propertly.
 */
interface ClonableControllerInterface
{
    /**
     * Method to duplicate all the entities in another language
     *
     * @param integer $foreign_key the ID of the related entity if needed
     * @return void Redirects on success, renders view otherwise.
     */
    public function duplicateAll($foreign_key = null);

    /**
     * Method to duplicate an entity depending on the request_data parameters
     *
     * @param integer $id the ID of the entity
     * @return void Redirects on success, renders view otherwise.
     */
    public function duplicate($id);
}
