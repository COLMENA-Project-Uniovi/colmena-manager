<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\Query;

class ExportableBehavior extends Behavior
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
        'export_fields' => [
            'id' => 'ID',
            'created' => 'Fecha de creación',
            'modified' => 'Fecha de modificación'
        ],
        'order' => [
            'created' => 'ASC'
        ]
    ];

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->setConfig($config, null, false);

        parent::__construct($table, $config);
    }

    /**
     * Export entities of the given type to CSV file
     *
     * @return string with the contents in CSV format
     */
    public function export($items = null)
    {
        $fields = $this->_config['export_fields'];

        // Get the entities with its fields and order
        $entities = $this->_table->find('all')
            ->select(array_keys($fields))
            ->order($this->_config['order']);

        if(!is_null($items)){
            $arrayItems = explode('-', $items);
            $entities = $entities->where(['status IN' => $arrayItems]);
        }
        // Generate the lines to insert into the CSV file
        $lines = [array_values($fields)];
        foreach ($entities as $entity) {
            $line = [];
            foreach ($fields as $name => $label) {
                array_push($line, $entity[$name]);
            }
            array_push($lines, $line);
        }

        // Create temporal file and get its CSV contents
        $contents = false;
        $handle = fopen('php://temp', 'r+');
        foreach ($lines as $line) {
            fputcsv($handle, $line);
        }
        rewind($handle);
        while (!feof($handle)) {
            $contents .= fgets($handle);
        }
        fclose($handle);

        return $contents;
    }
}
