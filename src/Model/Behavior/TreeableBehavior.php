<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\Query;

class TreeableBehavior extends Behavior
{
    /**
     * Table instance
     *
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'implementedFinders' => ['asTree' => 'findAsTree'],
        'key_field' => 'id',
        'parent_field' => 'parent_id',
        'spacer' => '&nbsp;'
    ];

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     */
    public function __construct(Table $table, array $config = [])
    {
        parent::__construct($table, $config);
    }

    /**
     * Initialize behavior with config if needed
     *
     * @param  array  $config configuration of the behavior
     *
     * @return void
     */
    public function initialize(array $config): void
    {
        // Some initialization code here
    }

    /**
     * Find the entities as a tree to show in a HTML select
     *
     * @param  \Cake\ORM\Query  $query   original query
     * @param  array            $options with the folder to search for
     *
     * @return \Cake\ORM\Query with the modified query
     */
    public function findAsTree(Query $query, array $options)
    {
        $query->find('threaded', [
            'keyField' => $this->_config['key_field'],
            'parentField' => $this->_config['parent_field'],
            'applyBehaviors' => false
        ])
            ->select(['id', 'parent_id', 'name'])
            ->formatResults(function ($results) {
                return $this->_formatAsTree($results);
            });

        return $query;
    }

    /**
     * Recursive method to format the output of entities to show them in
     * a nested way with a spacer character
     *
     * @param Collection $entities the entities to format as a tree
     * @param integer    $level    the level of depth
     * @return array of entities nested with the spacer
     */
    private function _formatAsTree($entities, $level = 0)
    {
        $formatted_array = [];
        foreach ($entities as $entity) {
            if (($level == 0 && $entity['parent_id'] == 0) || $level != 0) {
                $array_name = str_repeat($this->_config['spacer'], $level * 4) . $entity['name'];
                $formatted_array[$entity['id']] = $array_name;
                if (!empty($entity['children'])) {
                    $new_level = $level + 1;
                    $children_array = $this->_formatAsTree($entity['children'], $new_level);
                    foreach ($children_array as $key_child => $child) {
                        $formatted_array[$key_child] = $child;
                    }
                }
            }
        }
        return $formatted_array;
    }
}
