<?php

namespace Colmena\AcademicalManager\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use App\Encryption\EncryptTrait;

/**
 * Student Model.
 *
 */
class ProjectsTable extends AppTable
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

    $this->setTable('acm_projects');
    $this->setDisplayField('title');
    $this->setPrimaryKey('id');

    $this->hasMany('Subjects', [
      'foreignKey' => 'project_id',
      'bindingKey' => 'id',
      'className' => 'Colmena/AcademicalManager.Subjects'
    ]);

    $this->hasOne('AdminUsers', [
      'foreignKey' => 'id',
      'className' => 'AdminUsers'
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
    $validator = parent::validateField('name', $validator);
    return $validator;
  }
}
