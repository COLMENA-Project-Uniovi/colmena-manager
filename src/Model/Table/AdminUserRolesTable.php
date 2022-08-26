<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use App\Model\Entity\AdminUserRoles;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdminUserRoles Model
 */
class AdminUserRolesTable extends AppTable
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

        $this->setTable('admin_user_roles');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Booleable');

        $this->hasMany('AdminUserRolesPermissions', [
            'foreignKey' => 'role_id',
            'className' => 'AdminUserRolesPermissions'
        ]);

        $this->belongsTo('AdminUsers', [
            'foreignKey' => 'role_id',
            'className' => 'AdminUsers'
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

        return $validator;
    }


    /**
     * Create the user roles permissions
     *
     * @param int $role_id
     * @param string $data the array with the user permissions
     * @return EntityInterface|false
     */
    public function createUserRolesPermissions($role_id, $data)
    {
        $role = $this->get($role_id);

        $role->user_roles_permissions = [];
        foreach ($data as $key => $value) {
            $model = str_replace('_', '.', $key);
            $actions = $value;

            if ($actions != 'none') {
                $permission = $this->AdminUserRolesPermissions->find('all')->where([
                    'role_id' => $role_id,
                    'model' => $model
                ])->first();

                if (!$permission) {
                    $permission = $this->AdminUserRolesPermissions->newEmptyEntity();
                    $permission->role_id = $role_id;
                    $permission->model = $model;
                }

                $permission->actions = $actions;
                $this->AdminUserRolesPermissions->save($permission);

                array_push($role->user_roles_permissions, $permission);
            }
        }

        return $this->save($role);
    }

    /**
     * Return the users role
     *
     * @param AdminUser $user
     * @return AdminUserRole
     */
    public function getRoleFromUser($user)
    {
        if (isset($user)) {
            $role = $this->find('all')->where(['id' => $user['role_id']])->first();
        } else {
            $role = null;
        }
        return $role;
    }
}
