<?php

namespace App\Utility;

use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;

/**
 * CacheUtility class for managing cache operations
 * for minimizing database access on the front-end petitions.
 */
class CacheUtility
{
    /**
     * Check if cache is is_active on the current scenario.
     *
     * @return bool cache flag
     */
    public function isis_active()
    {
        $config_table = TableRegistry::getTableLocator()->get('Configs');
        $config = $config_table->find('all')->where(['name' => 'cache'])->first();

        return $config && $config->value == 'true' ? true : false;
    }

    /**
     * Get a value from the cache.
     *
     * @param [type] $key      value's key
     * @param [type] $cache_id cache identifier
     */
    public function get($key, $cache_id)
    {
        $cache_is_active = self::isis_active();
        if ($cache_is_active) {
            $cached = Cache::read($key, $cache_id);

            return $cached != false ? json_decode($cached) : false;
        }

        return false;
    }

    /**
     * Store a value in the cache.
     *
     * @param [type] $key      value's key
     * @param [type] $data     data to store
     * @param [type] $cache_id cache identifier
     */
    public function set($key, $data, $cache_id)
    {
        $cache_is_active = self::isis_active();
        if ($cache_is_active) {
            return Cache::write($key, json_encode($data), $cache_id);
        }

        return true;
    }

    /**
     * Delete a value from the cache.
     *
     * @param [type] $key      value's key
     * @param [type] $cache_id cache identifier
     */
    public function delete($key, $cache_id)
    {
        $cache_is_active = self::isis_active();
        if ($cache_is_active) {
            return Cache::delete($key, $cache_id);
        }

        return false;
    }

    /**
     * Clear all information stored in cache.
     *
     *
     * @return bool
     */
    public function clear()
    {
        $cache_is_active = self::isis_active();

        if ($cache_is_active) {
            Cache::clearAll();
        }

        return true;
    }
}
