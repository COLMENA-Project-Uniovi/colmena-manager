<?php

namespace Colmena\AcademicalManager\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use App\Encryption\EncryptTrait;

/**
 * Student Model.
 *
 */
class SubjectsTable extends AppTable
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

        $this->setTable('acm_subjects');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Projects',
            [
                'className' => 'Colmena/AcademicalManager.Projects',
            ]
        );

        $this->hasMany('Sessions', [
            'foreignKey' => 'subject_id',
            'className' => 'Colmena/AcademicalManager.Sessions'
        ]);

        $this->belongsTo(
            'AcademicalYear',
            [
                'className' => 'Colmena/AcademicalManager.AcademicalYears',
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
