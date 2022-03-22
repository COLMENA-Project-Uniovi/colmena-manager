<?php

namespace App\Utility;

use Cake\ORM\TableRegistry;

/**
 * NotificationUtility class for managing notification operations
 */
class NotificationUtility
{
    /**
     * Check if the action is active.
     *
     * @return bool notification is active
     */
    public function isActive($model, $action)
    {
        if($model && $action) {
            $notifications_table = TableRegistry::getTableLocator()->get('Notifications');

            $notification = $notifications_table->find()
            ->where([
                'model' => $model,
                'action' => $action,
                'active' => true,
            ])
            ->first();

            return !is_null($notification);
        }

        return false;
    }
}
