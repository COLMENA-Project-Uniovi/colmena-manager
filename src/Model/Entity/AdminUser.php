<?php

namespace App\Model\Entity;

// Password Hashing
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class AdminUser extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id' => false,
        '*' => true
    ];

    /**
     * Hidden fields to not show in JSON responses
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * Changes the password using the hashing string to store secure passwords
     *
     * @param string $value to be hashed
     *
     * @return the new hashed password
     */
    protected function _setPassword($value)
    {
        if ($value != '') {
            $hasher = new DefaultPasswordHasher();
            return $hasher->hash($value);
        }
        return $value;
    }
}
