<?php

namespace App\Clonable;

use Cake\Core\Configure;
use Cake\I18n\I18n;

/**
 * Trait to provide common methods to Clonable objects.
 */
trait ClonableTrait
{
    /**
     * Copy the SEO content of an entity
     *
     * @param  array  $seo  the original SEO fields of the entity
     * @param  string $folder_suffix the suffix to add to the folder field
     *
     * @return array with the new SEO content
     */
    public function copySeo($seo, $folder_suffix)
    {
        if (isset($seo['general']['folder']) && $seo['general']['folder'] != '') {
            $seo['general']['folder'] .= $folder_suffix;
        }

        unset($seo['folder']);

        return $seo;
    }

    /**
     * Copy the media resources of an entity and format them to be saved properly
     * by the MediaBehavior.
     *
     * @param  array $original_media with the original media of the entity
     *
     * @return array with the new media properly formatted
     */
    public function copyMedia($original_media)
    {
        $medias = [];
        foreach ($original_media as $key => $media) {
            if (isset($media['id'])) {
                $medias[$key][0] = ['path' => $media['path']];
            } else {
                //es una galeria
                if (is_array($media) & !empty($media)) {
                    $medias[$key] = [];
                    foreach ($media as $image) {
                        if (isset($image['id'])) {
                            array_push($medias[$key], ['path' => $image['path']]);
                        }
                    }
                }
            }
        }

        return $medias;
    }

    /**
     * Copy the original buttons of an entity and format them properly
     *
     * @param array $original_buttons with the original buttons
     * @return array with the new buttons properly formatted
     */
    public function copyButtons($original_buttons)
    {
        if (empty($original_buttons)) {
            return [];
        }
        $buttons = $original_buttons->toArray();
        unset($buttons['id']);
        unset($buttons['foreign_key']);
        $buttons['buttons'] = json_encode($buttons['buttons'], true);

        return $buttons;
    }

    /**
     * Copy the original parameters of an entity and format them properly
     *
     * @param array $original_parameter with the original parameter
     * @return array with the new parameter properly formatted
     */
    public function copyParameters($original_parameter)
    {
        if (empty($original_parameter)) {
            return [];
        }
        $parameter = $original_parameter;
        if(!is_array($parameter)) {
            $parameter = $parameter->toArray();
        }
        unset($parameter['id']);
        unset($parameter['foreign_key']);
        $parameter['content'] = $parameter['content'];

        return $parameter;
    }

    /**
     * Change the locale of the application
     *
     * @param string $locale with the locale code
     */
    protected function setLocale($locale = null)
    {
        if ($locale != null && in_array($locale, array_keys(Configure::read('I18N.locales')))) {
            I18n::setLocale($locale);
        } else {
            I18n::setLocale(Configure::read('I18N.language'));
        }
    }
}
