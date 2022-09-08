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

        $this->hasOne('Sessions', [
            'className' => 'Colmena/AcademicalManager.Sessions',
            'foreignKey' => 'id',
        ]);

        $this->belongsToMany(
            'Conflicts',
            [
                'className' => 'Colmena/AcademicalManager.Sessions',
                'joinTable' => 'em_markers_conflicts',
                'foreignKey' => 'marker_id',
                'targetForeignKey' => 'session_id',
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
