<?php

namespace Colmena\UsersManager\Model\Table;

use Colmena\UsersManager\Model\Entity\User;
use App\Model\Table\AppTable;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use App\Encryption\EncryptTrait;

/**
 * PracticeGroups Model.
 *
 */
class PracticeGroupsTable extends AppTable
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

        $this->setTable('um_practice_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany(
            'Users',
            [
                'className' => 'Colmena/UsersManager.Users',
                'joinTable' => 'um_practice_groups_users',
                'foreignKey' => 'practice_group_id',
                'targetForeignKey' => 'user_id',
            ]
        );

        $this->hasOne('Schedules', [
            'className' => 'Colmena/AcademicalManager.SessionSchedules',
            'foreignKey' => 'practice_group_id',
            'bindingKey' => 'id'
        ]);

        $this->belongsTo(
            'Supervisor',
            [
                'className' => 'AdminUsers',
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
}
