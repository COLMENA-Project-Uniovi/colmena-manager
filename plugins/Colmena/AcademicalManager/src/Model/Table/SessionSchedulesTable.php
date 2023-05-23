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
    $this->setPrimaryKey('id');

    $this->addBehavior('Timestamp');

    $this->belongsTo(
      'Sessions',
      [
        'className' => 'Colmena/AcademicalManager.Sessions',
      ]
    );

    $this->belongsTo('PracticeGroups', [
      'className' => 'Colmena/UsersManager.PracticeGroups'
    ])->setForeignKey('practice_group_id');
  }
}
