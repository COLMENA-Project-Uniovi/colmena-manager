<?php

namespace App\Model\Table;

use Cake\ORM\Association\BelongsToMany;
use Cake\Validation\Validator;

/**
 * Contact Model
 *
 * @property BelongsToMany $ArticleTags
 */
class ContactsTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('contacts');
        $this->setPrimaryKey('id');

        // TimeStamp behavior
        $this->addBehavior('Timestamp');

        // Booleable behavior to change boolean entities through ajax
        $this->addBehavior('Booleable');

        // Address behavior
        $this->addBehavior('Address');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return void
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator = parent::validateId($validator);

        $validator
            ->requirePresence('name', 'create');

        return $validator;
    }
}
