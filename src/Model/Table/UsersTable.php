<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ConnectionManager;
use ArrayObject;

/**
 * Users Model
 *
 */
class UsersTable extends AppTable
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

        $this->hasMany('UserRoles', [
            'className' => 'UserRoles',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
            'saveStrategy' => 'replace',
            'sort' => ['name' => 'ASC']
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
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
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
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
     * @param  \Cake\Event\Event $event The beforeSave event that was fired
     * @param  \Cake\Datasource\EntityInterface $entity The entity that is going to be saved
     * @param  \ArrayObject $options the options passed to the save method
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

    /**
     * Create the user roles
     *
     * @param int $user_id
     * @param string $data the array with the user permissions
     * @return void
     */
    public function createUserRoles($user_id, $data)
    {
        $user = $this->get($user_id);

        $user->user_roles = [];
        foreach ($data as $key => $value) {
            $entity = str_replace('_', '.', $key);
            $actions = $value;

            if ($actions != 'none') {
                $role = $this->UserRoles->newEmptyEntity();
                $role->entity = $entity;
                $role->actions = $actions;
                array_push($user->user_roles, $role);
            }
        }

        return $this->save($user);
    }
}
