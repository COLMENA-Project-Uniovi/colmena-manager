<?php

namespace Colmena\ErrorsManager\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Marker Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $excerpt
 * @property string $description
 * @property bool $is_visible
 * @property bool $featured
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Marker extends Entity
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
        'id'
    ];
}
