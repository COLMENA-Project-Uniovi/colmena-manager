<?php

namespace Colmena\ErrorsManager\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use App\Encryption\EncryptTrait;

/**
 * Student Model.
 *
 */
class ErrorExamplesTable extends AppTable
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

    $this->setTable('em_error_examples');
    $this->setDisplayField('name');
    $this->setPrimaryKey('id');

    $this->belongsTo(
      'Users',
      [
        'className' => 'AdminUsers',
      ]
    );

    $this->belongsTo(
      'Sessions',
      [
        'className' => 'Colmena/AcademicalManager.Sessions',
      ]
    );

    $this->belongsTo(
      'Errors',
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
