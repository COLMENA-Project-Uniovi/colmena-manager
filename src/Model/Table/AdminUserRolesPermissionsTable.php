<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use App\Model\Entity\AdminUserRolesPermission;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserRoles Model
 */
class AdminUserRolesPermissionsTable extends AppTable
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

        $this->setTable('admin_user_roles_permissions');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AdminUserRoles', [
            'foreignKey' => 'role_id',
            'className' => 'AdminUserRoles'
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
            ->add('role_id', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('create');

        $validator
            ->requirePresence('model', 'create')
            ->notEmptyString('model');

        $validator
            ->requirePresence('actions', 'create')
            ->notEmptyString('actions');

        return $validator;
    }
}
