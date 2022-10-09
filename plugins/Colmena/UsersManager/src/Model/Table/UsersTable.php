<?php

namespace Colmena\UsersManager\Model\Table;

use Colmena\UsersManager\Model\Entity\User;
use App\Model\Table\AppTable;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use App\Encryption\EncryptTrait;

/**
 * Student Model.
 *
 */
class UsersTable extends AppTable
{
    use EncryptTrait;
    
    /**
     * Initialize method.
     *
     * @param array $config the configuration for the Table
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('um_users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany(
            'Groups',
            [
                'className' => 'Colmena/UsersManager.PracticeGroups',
                'joinTable' => 'um_practice_groups_users',
                'foreignKey' => 'user_id',
                'targetForeignKey' => 'practice_group_id',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator instance
     *
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator = parent::validateId($validator);
        $validator = parent::validateField('name', $validator);
        return $validator;
    }


    public function login($data = null)
    {
        if (!isset($data) || empty($data)) {
            throw new InvalidArgumentException('Invalid login data');
        }
        $user = $this->find('all')->where(['email' => $data])->first();

        return $user;
    }
}
