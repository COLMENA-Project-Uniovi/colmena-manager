<?php

namespace Colmena\AcademicalManager\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use App\Encryption\EncryptTrait;

/**
 * SessionSchedulesTable Model.
 *
 */
class SessionSchedulesTable extends AppTable
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

        $this->setTable('acm_session_shedules');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Sessions',
            [
                'className' => 'Colmena/AcademicalManager.Sessions',
            ]
        );

        $this->hasOne(
            'PracticeGroups',
            [
                'className' => 'Colmena/AcademicalManager.PracticeGroups'
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
