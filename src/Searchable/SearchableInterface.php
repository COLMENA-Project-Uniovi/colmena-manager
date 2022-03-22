<?php

namespace App\Searchable;

/**
 * Searchable interface to find an entity by its URL.
 * Each entity we want to be processed by the
 * UrlController must implement this interface.
 */
interface SearchableInterface
{
    /**
     * Get an entity by its url.
     * If the url has more than one level, each level MUST be separated by "_"
     *
     * @param  string $url
     *
     * @return Entity with the entity found
     */
    public function getByUrl($url, $template = "");

    /**
     * Format the SEO folder for the Entity
     *
     * @param  Entity|Array  $entity        with all the content of the entity
     * @param  boolean       $friendly_mode to check if the seo is passed like an entity or friendly formatted
     * @param  string        $template the string of the template used
     *
     * @return Entity with the new SEO
     */
    public function formatSeo($entity, $friendly_mode = false, $template = 1);
}
