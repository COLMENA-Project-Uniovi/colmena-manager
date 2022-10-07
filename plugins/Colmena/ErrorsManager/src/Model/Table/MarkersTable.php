<?php

namespace Colmena\ErrorsManager\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Marker Model.
 *
 */
class MarkersTable extends AppTable
{
    /**
     * Initialize method.
     *
     * @param array $config the configuration for the Table
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('em_markers');
        $this->setPrimaryKey('id');

        // Session entity relation
        $this->belongsTo(
            'Session',
            [
                'className' => 'Colmena/AcademicalManager.Sessions',
            ]
        );

        // User entity relation
        $this->belongsTo(
            'Student',
            [
                'className' => 'Colmena/UsersManager.Users',
            ]
        )->setForeignKey('user_id');

        // Error entity relation
        $this->belongsTo(
            'Error',
            [
                'className' => 'Colmena/ErrorsManager.Errors',
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
