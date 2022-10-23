<?php

namespace Colmena\AcademicalManager\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use App\Encryption\EncryptTrait;

/**
 * Student Model.
 *
 */
class SessionsTable extends AppTable
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

        $this->setTable('acm_sessions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Subjects',
            [
                'className' => 'Colmena/AcademicalManager.Subjects',
            ]
        );

        $this->hasMany(
            'SessionSchedules',
            [
                'foreignKey' => 'session_id',
                'className' => 'Colmena/AcademicalManager.SessionSchedules'
            ]
        );

        $this->belongsTo(
            'Languages',
            [
                'className' => 'Colmena/ErrorsManager.Languages',
            ]
        );

        $this->hasMany('Markers', [
            'foreignKey' => 'session_id',
            'bindingKey' => 'id',
            'className' => 'Colmena/ErrorsManager.Markers'
        ]);

        $this->hasMany('Compilations', [
            'foreignKey' => 'session_id',
            'bindingKey' => 'id',
            'className' => 'Colmena/ErrorsManager.Compilations'
        ]);
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
