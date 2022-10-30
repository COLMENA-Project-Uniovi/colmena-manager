<?php

namespace Colmena\ErrorsManager\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Student Model.
 *
 */
class CompilationsTable extends AppTable
{

    /**
     * Initialize method.
     *
     * @param array $config the configuration for the Table
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('em_compilations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        // User entity relation
        $this->belongsTo(
            'Student',
            [
                'className' => 'Colmena/UsersManager.Users',
            ]
        )->setForeignKey('user_id');

        // Marker entity relation
        $this->hasMany(
            'Markers',
            [
                'foreignKey' => 'compilation_id',
                'bindingKey' => 'id',
                'className' => 'Colmena/ErrorsManager.Markers'
            ]
        );

        // Session entity relation
        $this->belongsTo(
            'Session',
            [
                'className' => 'Colmena/AcademicalManager.Sessions',
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
