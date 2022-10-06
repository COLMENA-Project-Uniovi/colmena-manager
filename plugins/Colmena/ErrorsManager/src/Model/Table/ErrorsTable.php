<?php

namespace Colmena\ErrorsManager\Model\Table;

use Colmena\UsersManager\Model\Entity\Error;
use App\Model\Table\AppTable;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use App\Encryption\EncryptTrait;

/**
 * Student Model.
 *
 */
class ErrorsTable extends AppTable
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

        $this->setTable('em_errors');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Family',
            [
                'className' => 'Colmena/ErrorsManager.ErrorsFamily',
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
        return $validator;
    }
}
