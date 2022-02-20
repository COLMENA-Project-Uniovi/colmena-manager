<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use App\Searchable\SearchableInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use ArrayObject;

/**
 * AppSearchableTable, intermediate implementation of Table for all the entities who need the SearchableInterface methods
 * TODO, CHECK IF getEntitySeo, getByURL and formatSEO are common for all child entities
 */
class AppSearchableTable extends AppTable implements SearchableInterface
{
    /**
     * Get an entity by its url.
     * Implementation of Searchable interface.
     *
     * @param  string $url of the entity
     *
     * @return Entity
     */
    public function getByUrl($url, $template = 1)
    {
        $split = explode('_', $url);

        if (count($split) < 2) {
            return false;
        }

        $folder = array_pop($split);
        // Find the entity
        $entity = $this
            ->find(
                'byFolder',
                ['folder' => $folder]
            )
            ->where([
                'is_visible' => '1'
            ])
            ->formatResults(function ($results) {
                return $results->map(function ($row) {
                    return $this->formatSeo($row);
                });
            })->first();

        // If entity not found
        if (!$entity) {
            return false;
        }

        // Find the entity section
        $section_folder = implode('_', $split);
        $section_table = TableRegistry::getTableLocator()->get('Neo/SectionsManager.Sections');
        $section = $section_table->getByUrl($section_folder, [
            'conditions' => [
                'template' => $template,
                'is_visible' => 1
            ]
        ]);

        // If the section is not entity
        if (!$section) {
            return false;
        }

        $entity['breadcrumbs'] = $section['breadcrumbs'];
        array_push(
            $entity['breadcrumbs'],
            [
                'name' => $entity['name'],
                'folder' => $entity['seo']['folder']
            ]
        );

        //TODO Check if it has to be in plural or singular
        $entity->template = $template;

        return $entity;
    }

    /**
     * Format the SEO folder for the entity
     * Implementation of Searchable interface.
     *
     * @param  Entity|Array  $entity        with all the content of the entity
     * @param  boolean       $friendly_mode to check if the seo is passed like an entity or friendly formatted
     * @param  string        $template the string of the template used
     *
     * @return Entity with the new SEO
     */
    public function formatSeo($entity, $friendly_mode = false, $template = 1)
    {
        $sectionsTable = TableRegistry::getTableLocator()->get('Neo/SectionsManager.Sections');
        $parent_section = $sectionsTable
            ->find('all')
            ->where([
                'is_visible' => 1,
                'id_template' => $template,
            ])
            ->first();
        
        if (!$parent_section) {
            return false;
        }
        
        // Get the full URL of the section
        $entity_url = $sectionsTable->getSectionUrl($parent_section);

        if (!$entity_url) {
            return false;
        }

        if (is_array($entity)) {
            $aux_entity = [];
            foreach ($entity as $ent) {
                $ent = $this->getEntitySeo($ent, $entity_url, $friendly_mode);
                if ($ent) {
                    array_push($aux_entity, $ent);
                }
            }
            $entity = $aux_entity;
        } else {
            $entity = $this->getEntitySeo($entity, $entity_url, $friendly_mode);
        }

        return $entity;
    }

    /**
     * Get the SEO of a single entity
     * Implementation of Searchable interface.
     *
     * @param  Entity  $entity        with all the content of the entity
     * @param  string  $base_url      with the base URL of the entity SEO
     * @param  boolean $friendly_mode to check if the seo is passed like an entity or friendly formatted
     *
     * @return Entity with the new SEO
     */
    protected function getEntitySeo($entity, $base_url, $friendly_mode = false)
    {
        if ($friendly_mode) {
            if (!isset($entity['seo']['folder'])) {
                return false;
            }
            $entity['seo']['folder'] = $base_url . $entity['seo']['folder'] . '/';
        } else {
            $seo = [];
            $folder_found = false;
            foreach ($entity['seo'] as $seo_field) {
                if ($seo_field['field'] == 'folder') {
                    $folder_found = true;
                    $seo_field['content'] = $base_url . $seo_field['content'] . '/';
                }
                array_push($seo, $seo_field);
            }
            if (!$folder_found) {
                return false;
            }
            $entity['seo'] = $seo;
        }

        return $entity;
    }
}
