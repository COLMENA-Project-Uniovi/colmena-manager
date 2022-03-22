<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use App\Model\Entity\Notification;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use ArrayObject;

/**
 * Notification Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $ArticleTags
 */
class NotificationsTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('notifications');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        // Booleable behavior to change boolean entities through ajax
        $this->addBehavior('Booleable');
    }

    /**
     * Modifies the entity before it is saved so that seo fields are persisted
     * in the database too.
     *
     * @param  \Cake\Event\Event $event The beforeSave event that was fired
     * @param  \Cake\Datasource\EntityInterface $entity The entity that is going to be saved
     * @param  \ArrayObject $options the options passed to the save method
     *
     * @return void
     */
    public function beforeSave(\Cake\Event\EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->plugin && $entity->entity) {
            $entity->model = $entity->plugin . "." . $entity->entity;
        }
    }

    protected $_virtual = ['plugin', 'entity'];

    protected function _getPlugin()
    {
        return explode(".", $this->_properties['model'])[0];
    }
    protected function _getEntity()
    {
        return explode(".", $this->_properties['model'])[1];
    }
}
