<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use ArrayObject;

/**
 * App Table, general for any model.
 */
class AppTable extends Table
{
    protected $displayName = [
        'singular' => "",
        'plural' => "",
    ];

    /**
     * Get the name to display in layouts. The name is defined in the table as an array, with diferent variants
     *
     * @param string $variant the variant of the string returned
     * @return string the value to whot in layouts
     */
    public function getDisplayName($variant = "singular")
    {
        return $this->displayName[$variant];
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        //validate the ID first
        return $validator = $this->validateId($validator);
    }

    /**
     * Generic function to validate the main ID
     *
     * @param Validator $validator the object for validation
     * @return void
     */
    public function validateId(Validator $validator)
    {
        return $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('id', 'create');
    }

    /**
     * Standard functino to validate a field as notEmpty. Usually will be 'title' or 'name'
     *
     * @param string $fieldName the field name to validate
     * @param Validator $validator the object for validation
     * @return Validator
     */
    public function validateField($fieldName, Validator $validator)
    {
        return  $validator
            ->requirePresence($fieldName, 'create')
            ->notEmptyString($fieldName);
    }
}
