<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\Utility\Text;
use Cake\I18n\I18n;
use Cake\Core\Configure;
use Cake\I18n\Time;

/**
 * Seo behavior to add SEO capabilities to an entity.
 * This behavior automatically manages SEO fields in order to be saved or populated
 * automatically for each entity.
 */
class SeoBehavior extends Behavior
{
    /**
     * Table instance.
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
        'implementedFinders' => ['byFolder' => 'findByFolder'],
        'referenceName' => '',
        'types' => [
            'general' => true,
            'mobile' => true,
            'og' => true,
            'twitter' => true,
            'metrics' => true,
        ],
        'uniqueFolder' => false,
        'seoTable' => 'Seo',
    ];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table  the table this behavior is attached to
     * @param array           $config the config for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
        $config += [
            'referenceName' => $table->getRegistryAlias(),
        ];

        parent::__construct($table, $config);
    }

    /**
     * Initialize hook.
     *
     * @param array $config the config for this behavior
     */
    public function initialize(array $config): void
    {
       
        // Set the SEO types configuration properly
        $this->_setTypesConfig($config);

        $this->_seoTable = TableRegistry::getTableLocator()->get($this->_config['seoTable']);
        
        $seoAlias = $this->_seoTable->getAlias();
        $alias = $this->_table->getAlias();

        /*
         * Association between Entity and Seo models
         */
        // Seo belongsTo Entity
        $this->_seoTable->belongsTo($alias, [
            'className' => $this->_config['referenceName'],
            'foreignKey' => 'foreign_key',
        ]);

        // Entity hasMany Seo
        $this->_table->hasMany($seoAlias, [
            'className' => $seoAlias,
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => [
                $seoAlias.'.model' => $this->_config['referenceName'],
            ],
        ]);
    }

    /**
     * Callback method that listens to the `beforeFind` event in the bound
     * table. It modifies the passed query by eager loading the seo fields
     * and adding a formatter to set the SEO values properly.
     *
     * @param \Cake\Event\Event $event   the beforeFind event that was fired
     * @param \Cake\ORM\Query   $query   Query
     * @param \ArrayObject      $options The options for the query
     */
    public function beforeFind(\Cake\Event\EventInterface $event, Query $query, $options)
    {
        // If we don't want to apply the before find actions use applyBehaviors option
        if (isset($options['applyBehaviors']) && !$options['applyBehaviors']) {
            return $query;
        }
        if (!isset($query->getContain()[$this->_seoTable->getAlias()])) {
            $query->contain([$this->_seoTable->getAlias()]);
        }

        // Check if select is empty
        if(!empty($query->clause("select"))) {
            $query->select([ $this->_table->getAlias() . '.id' ]);
        }

        $query
            ->formatResults(function ($results) {
                return $results->map(function ($row) {
                    if (!isset($row['seo']) ||
                        empty($row['seo'])) {
                        return $row;
                    }

                    // Get the current configuration of the types
                    $types_config = $this->_config['types'];

                    $seo_aux = [];
                    $folder_field = [];
                    foreach ($row['seo'] as $seo) {
                        // Check if the current database SEO field is configured
                        // in the SEO configuration of the Entity
                        if (isset($seo->locale) &&
                            isset($seo->type) &&
                            $seo['locale'] == I18n::getLocale() &&
                            isset($types_config[$seo->type]) &&
                            isset($types_config[$seo->type]['fields'][$seo->field])) {
                            $seo_aux[$seo->type][$seo->field] = $seo->content;

                            if ($seo->field == 'folder') {
                                $folder_field = [
                                    $seo->field => $seo->content,
                                ];
                            }
                        }
                    }

                    $row['seo'] = array_merge($folder_field, $seo_aux);

                    return $row;
                });
            });
        }

    /**
     * Modifies the structure of the data that will be passed to the patchEntity.
     *
     * @param \Cake\Event\Event $event   the beforeMarshal event that was fired
     * @param \ArrayObject      $options The data of the entity
     * @param \ArrayObject      $options The options for the query
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (!isset($data['seo'])) {
            $data['_seo'] = [];
        } else {
            // Get the SEO config for this entity
            $seo_config = $this->_config['types'];

            // Unset the aux field folder so it is not saved to the database
            unset($data['seo']['folder']);
            // Use aux variable while iterating over original
            $aux_seo = $data['seo'];

            // Check if all the SEO fields are set according to the config
            // If not, set them empty to the $aux_seo array
            foreach ($seo_config as $type => $type_config) {
                foreach ($type_config['fields'] as $field => $config) {
                    if (!isset($aux_seo[$type][$field])) {
                        $aux_seo[$type][$field] = '';
                    }
                }
            }

            // If the SEO data is empty, fill it with the aux_seo fields
            $data['seo'] = empty($data['seo']) ? $aux_seo : $data['seo'];

            // Check the fallbacks of each SEO field
            // If one of them has a fallback and its content is empty change
            // the value to the fallback field of the entity
            foreach ($data['seo'] as $type => $seo_data) {
                $type_config = $seo_config[$type];
                foreach ($seo_data as $field => $content) {
                    if (isset($type_config['fallbacks'][$field]) &&
                        trim($content) == '') {
                        $entity_field = $type_config['fallbacks'][$field];
                        $field_content =
                            isset($data[$entity_field]) ?
                            $data[$entity_field] :
                            false;
                        if ($field_content === false) {
                            $field_content =
                                isset($options['entity'][$entity_field]) ?
                                $options['entity'][$entity_field] :
                                false;
                        }
                        if ($field_content !== false) {
                            $aux_seo[$type][$field] = strip_tags($field_content);
                        }
                    }
                }
            }

            $data['_seo'] = $aux_seo;
            unset($data['seo']);
        }
    }

    /**
     * Modifies the entity before it is saved so that seo fields are persisted
     * in the database too.
     *
     * @param \Cake\Event\Event                $event   The beforeSave event that was fired
     * @param \Cake\Datasource\EntityInterface $entity  The entity that is going to be saved
     * @param \ArrayObject                     $options the options passed to the save method
     */
    public function beforeSave(\Cake\Event\EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        // If we don't want to apply the before save actions use applyBehaviors option
        if (isset($options['applyBehaviors']) && !$options['applyBehaviors']) {
            return;
        }

        // If saving with associated option, attach seo table too
        if (isset($options['associated'])) {
            $options['associated'][$this->_seoTable->getAlias()] = [];
        }

        // Get the locale of the entity we are saving if it is set
        $locale = $entity->get('_locale') ? $entity->get('_locale') : $this->getLocale();
        $entity->setDirty('_seo', false);
        // Get the current SEO of the entity
        $current_seo = $entity['seo'];
        // Get the new SEO we want to save
        $new_seo = isset($entity['_seo']) ? $entity['_seo'] : [];

        // Get the primary key field name
        $primaryKey = (array) $this->_table->getPrimaryKey();
        $key = $entity->get(current($primaryKey));
        // Get the name of the entity model
        $model = $this->_config['referenceName'];

        // Ser two arrays, one of them with numeric keys and the other with keys
        // using the type and fields of each SEO entity.
        // This is usefull for comparing arrays later and saving the entity properly.
        $modified = [];
        $aux_modified = [];
        if (!empty($current_seo)) {
            // Get the preexistent SEO fields in the database for this entity
            $preexistent = $this->_seoTable->find()
                ->select(['id', 'type', 'field'])
                ->where(['locale' => $locale, 'foreign_key' => $key, 'model' => $model])
                ->enableBufferedResults(false)
                ->indexBy(function ($row) {
                    return $row['type'].'.'.$row['field'];
                });

            if (!empty($new_seo)) {
                // Check if the SEO we are trying to save is already persisted
                // in the database so we don't save another field with the same config
                foreach ($preexistent as $index => $seo) {
                    $index_parts = explode('.', $index);
                    $type = $index_parts[0];
                    $field = $index_parts[1];

                    if ($field == 'folder') {
                        $new_seo[$type][$field] = strtolower(Text::slug($new_seo[$type][$field]));

                        // Check if folder is duplicated
                        if ($new_seo[$type][$field] == '' ||
                            $this->_isDuplicateFolder($new_seo[$type][$field], $locale, $key, $model)) {
                            /*
                             * TODO
                             * The error doesn't show properly in the HTML form below the field
                             * The error do show in the general error message at the top of the screen
                             */
                            $entity->errors('seo.general.folder', ['message' => 'La URL amigable debe ser única y no debe estar vacía.']);

                            return false;
                        }
                    }
                    $seo->set('content', $new_seo[$type][$field]);
                    // Set the below arrays for further processing
                    $aux_modified[$type][$field] = $seo;
                    array_push($modified, $seo);
                }
            }
        }

        $aux_new = [];
        // Get the new fields that are not in the database
        foreach ($new_seo as $type => $seo_data) {
            if (is_array($seo_data)) {
                foreach ($seo_data as $field => $content) {
                    if (!isset($aux_modified[$type][$field])) {
                        $aux_new[$type][$field] = $content;
                    }
                }
            }
        }

        $new = [];
        // For each new field, create a SEO entity with its data
        foreach ($aux_new as $type => $seo_data) {
            foreach ($seo_data as $field => $content) {
                if ($field == 'folder') {
                    $content = strtolower(Text::slug($content));

                    // Check if folder is duplicated
                    if ($content == '' && $this->_isDuplicateFolder($content, $locale, $key, $model)) {
                        /*
                         * TODO
                         * The error doesn't show properly in the HTML form below the field
                         * The error do show in the general error message at the top of the screen
                         */
                        $entity->errors('seo.general.folder', ['message' => 'La URL amigable debe ser única y no debe estar vacía.']);

                        return false;
                    }
                }
                $seo_entity = new Entity(compact('locale', 'model', 'type', 'field', 'content'), [
                    'useSetters' => false,
                    'markNew' => true,
                ]);
                array_push($new, $seo_entity);
            }
        }

        // Set the modified and new SEO fields back to the entity
        $entity->set('seo', array_merge($modified, $new));
    }

    /**
     * Modifies the entity before it is saved so that seo fields are persisted
     * in the database too.
     *
     * @param \Cake\Event\Event                $event   The beforeSave event that was fired
     * @param \Cake\Datasource\EntityInterface $entity  The entity that is going to be saved
     * @param \ArrayObject                     $options the options passed to the save method
     */
    public function afterSave(\Cake\Event\EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!Configure::read('debug')) {
            $this->updateHtaccess($entity);
        }
    }

    /**
     * Find an entity based on its seo folder field.
     *
     * @param \Cake\ORM\Query $query   original query
     * @param array           $options with the folder to search for
     *
     * @return \Cake\ORM\Query with the modified query
     */
    public function findByFolder(Query $query, array $options)
    {
        if (!isset($options['folder'])) {
            return $query;
        }

        // Get SEO that matches the options
        $locale = $this->getLocale();
        $results = $this->_seoTable
            ->find('all')
            ->where(
                [
                    'locale' => $locale,
                    'model' => $this->_config['referenceName'],
                    'type' => 'general',
                    'field' => 'folder',
                    'content' => $options['folder'],
                ]
            );

        // Get the ids of that records
        $ids = [];
        foreach ($results as $entity) {
            array_push($ids, $entity->foreign_key);
        }

        // Set the where conditions to find only desired results
        if (empty($ids)) {
            $ids = [0];
        }
        $query
            ->where([$this->_table->getAlias().'.id IN' => $ids]);

        return $query;
    }

    /**
     * Update the .htaccess file to add 301 redirects if the folder of the entity changes.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     *
     * @return bool if the file was saved successfully or not
     */
    private function updateHtaccess($entity)
    {
        $entity_seo = $entity->seo;
        $original_seo = $entity->getOriginal('seo');
        foreach ($entity->seo as $seo) {
            // Iterate over the current seo of the entity
            if ($seo->field == 'folder') {
                // If the seo field is "folder"
                foreach ($original_seo as $orig_seo) {
                    // Iterate over the original seo of the entity
                    if ($orig_seo->field == 'folder' &&
                        $orig_seo->locale == $this->getLocale() &&
                        $seo->content != $orig_seo->content) {
                        // If the original seo field is "folder" and
                        // if the locale is the same as the current and
                        // if the original folder is different as the current folder
                        $old_folder = $orig_seo->content;
                        $new_folder = $seo->content;

                        // Get the full folder of the entity using the formatSeo method
                        $aux_entity = $this->_table->formatSeo($entity, false);

                        $new_path = '';
                        foreach ($aux_entity->seo as $formatted_seo) {
                            if ($formatted_seo->field == 'folder') {
                                $new_path = $formatted_seo->content;
                                break;
                            }
                        }

                        // Get the original full folder of the entity using
                        // the new_path recently retrieved
                        $old_path_parts = explode('/', $new_path);

                        array_pop($old_path_parts);
                        array_pop($old_path_parts);
                        array_push($old_path_parts, $old_folder);

                        $old_path = implode('/', $old_path_parts).'/';

                        // Create the redirect entry in the .htaccess
                        $redirect_date = Time::now()->i18nFormat('yyyy-MM-dd HH:mm:ss');
                        $redirect = "\t\t# 301 Redirect\n";
                        $redirect .= "\t\t# Generated by ".$_SESSION['Auth']['User']['username'].' on '.$redirect_date."\n";
                        $redirect .= "\t\tRewriteRule ^".$old_path.'$ /'.$new_path." [R=301,NC,L]\n";
                        $redirect .= "\n";

                        $redirect_inverse_comment = "\t\t# Commented by ".$_SESSION['Auth']['User']['username'].' on '.$redirect_date." due to inconsistency\n";
                        $redirect_inverse = 'RewriteRule ^'.$new_path.'$ /'.$old_path.' [R=301,NC,L]';

                        // Write entry to htacces file (301 redirect)
                        $htaccess_dir = Configure::read('Config.htaccess_dir');
                        $lines = [];
                        // Read the file line by line
                        foreach (file($htaccess_dir) as $line) {
                            if ($redirect_inverse === trim($line)) {
                                array_push($lines, $redirect_inverse_comment);

                                $line = "\t\t# ".trim($line)."\n";
                            }

                            array_push($lines, $line);
                            if ('# Add admin redirects here' === trim($line)) {
                                array_push($lines, $redirect);
                            }
                        }
                        file_put_contents($htaccess_dir, $lines);

                        return;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get the seo configuration so it can be consumed in the views.
     * This is usefull to autogenerate seo-block divs.
     *
     * @return array with the seo config for this entity
     */
    public function getSeoConfig()
    {
        return $this->_config['types'];
    }

    /**
     * Sets the locale that should be used for all future find and save operations on
     * the table where this behavior is attached to.
     *
     * @param string|null $locale The locale to use for fetching and saving records. Pass `null`
     *                            in order to unset the current locale, and to make the behavior fall back to using the
     *                            globally configured locale.
     *
     * @return $this
     */
    private function setLocale($locale = null)
    {
        $this->_locale = $locale != null ? $locale : I18n::getLocale();

        return $this;
    }

    /**
     * Returns the current locale.
     *
     * If no locale has been explicitly set via `setLocale()`, this method will return
     * the currently configured global locale.
     *
     * @return string
     */
    private function getLocale()
    {
        return isset($this->_locale) && $this->_locale ? $this->_locale : I18n::getLocale();
    }

    /**
     * Check if a folder is duplicated for the current model.
     * If there is a folder field with the content duplicated and related to
     * another entity, return true.
     *
     * @param string $content     content of the field
     * @param string $locale      language to seach for
     * @param int    $foreign_key of the entity we are adding/editing
     * @param string $model       name of the model
     *
     * @return bool if the content is duplicated
     */
    private function _isDuplicateFolder($content, $locale, $foreign_key, $model)
    {
        // If the current model is configured to have unique folder
        if (!$this->_config['uniqueFolder']) {
            return false;
        }
        $conditions = [
            'locale' => $locale,
            'model' => $model,
            'type' => 'general',
            'field' => 'folder',
            'content' => $content,
        ];
        if ($foreign_key) {
            $conditions['foreign_key !='] = $foreign_key;
        }
        $old_field = $this->_seoTable->find()
            ->where($conditions);

        if (!empty($old_field->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * Set the config for this entity using the default configuration
     * and the configuration present in the Entity Table.
     *
     * @param array $config with the config of the Entity Table
     */
    private function _setTypesConfig($config)
    {
        // Get the default SEO config
        $seo_vars = Configure::read('SEO');
        $aux_config = $this->_config;
        $aux_types = [];

        // If no SEO config set, use default
        if (!isset($config['types'])) {
            $aux_types = $seo_vars;
        } else {
            // Remove SEO types that we don't what to attach to the entity and
            // merge that with the default SEO config
            foreach ($aux_config['types'] as $type => $type_config) {
                // If no SEO type set, use default
                if (!isset($config['types'][$type])) {
                    $aux_types[$type] = $seo_vars[$type];
                } elseif (isset($config['types'][$type]) &&
                    $config['types'][$type] !== false) {
                    if (is_array($type_config) && isset($seo_vars[$type])) {
                        // If modifying the config of a existing default type
                        $aux_types[$type] = array_merge($seo_vars[$type], $type_config);
                    } elseif (!isset($seo_vars[$type])) {
                        // If adding a completely new type
                        $aux_types[$type] = $config['types'][$type];
                    } else {
                        // Else, use default config
                        $aux_types[$type] = $seo_vars[$type];
                    }
                }
            }
        }

        $aux_config['types'] = $aux_types;
        $this->_config = $aux_config;
    }
}
