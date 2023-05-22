<?php

namespace Colmena\UsersManager\Model\Table;

use Colmena\UsersManager\Model\Entity\User;
use App\Model\Table\AppTable;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use App\Encryption\EncryptTrait;
use Cake\ORM\RulesChecker;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Student Model.
 *
 */
class UsersTable extends AppTable
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

		$this->setTable('um_users');
		$this->setDisplayField('name');
		$this->setPrimaryKey('id');

		$this->belongsToMany(
			'Groups',
			[
				'className' => 'Colmena/UsersManager.PracticeGroups',
				'joinTable' => 'um_practice_groups_users',
				'foreignKey' => 'user_id',
				'targetForeignKey' => 'practice_group_id',
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

		$validator
			->requirePresence('username', 'create')
			->notEmptyString('username');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param RulesChecker $rules The rules object to be modified.
	 * @return RulesChecker
	 */
	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->add($rules->isUnique(['username'], 'El nombre de usuario ya existe'));
		return $rules;
	}

	/**
	 * Login method.
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function login($data = null)
	{
		if (!isset($data) || empty($data)) {
			throw new InvalidArgumentException('Invalid login data');
		}

		$hasher = new DefaultPasswordHasher();
		$user = null;

		# Deppending on the type of user, we will use a different table to obtain the user
		switch ($data['type']) {
			case 'student':
				# In case the user is a student, we will use the current table
				$user = $this->find('all')->where(['username' => $data['username']])->contain(['Groups'])->first();
				# If the user exists, we check the password and then return the user or null in case the user doesnt' exist
				return isset($user) && $hasher->check($data['password'], $user['password']) ? $user : null;
			case 'teacher':
				# In case the user is a teacher, we will use the AdminUsers table
				$user = TableRegistry::getTableLocator()->get('AdminUsers')->find('all')->where(['username' => $data['username']])->first();
				# If the user exists, we check the password and then return the user or null in case the user doesnt' exist
				return isset($user) && $hasher->check($data['password'], $user['password']) ? $user : null;
			default:
				return null;
				break;
		}
	}
}
