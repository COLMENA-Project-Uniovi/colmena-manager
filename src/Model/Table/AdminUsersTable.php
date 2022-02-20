<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * Users Model
 *
 */
class AdminUsersTable extends AppTable
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


        $this->setTable('admin_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // Booleable behavior to change boolean entities through ajax
        $this->addBehavior('Booleable');

        $this->hasOne('AdminUserRoles', [
            'className' => 'AdminUserRoles',
            'foreignKey' => 'role_id',
        ]);

        $this->hasOne('Restaurant',[
            'foreignKey' => 'id',
            'targetForeignKey' => 'restaurant_id',
            'className' => 'Neo/ReservationsManager.Restaurants',
        ]);
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
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->add('password', 'length', [
                'rule' => ['minLength', 6],
                'message' => 'La contraseña debe tener al menos 6 caracteres.'
            ])
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'Debes introducir una contraseña', 'create')
            ->allowEmptyString('password', 'update');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username'], 'El nombre de usuario ya existe'));
        return $rules;
    }

    /**
     * Modifies the entity before it is saved so that seo fields are persisted
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
        // If the password is empty leave the previous value
        if ($entity->password == '' || $entity->password == '********') {
            unset($entity->password);
        }
    }

}
