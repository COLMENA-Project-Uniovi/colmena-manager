<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Event\Event;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * InlineCss helper
 *
 * This helper uses a CSS Inliner tool to inline the css to send HTML emails
 * with Mailgun.
 */
class InlineCssHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * After layout logic.
     *
     * @param string $layoutFile
     * @return void
     */
    public function afterLayout(Event $event, $layoutFile)
    {
        //parent::afterLayout($event, $layoutFile);
        $content = $this->getView()->fetch('content');

        // We only want to apply inline CSS to HTML emails, so first check if
        // the content is HTML before proceeding.
        if ($this->isHtml($content) === false) {
            return;
        }
        if (!isset($this->InlineCss)) {
            $this->InlineCss = new CssToInlineStyles();
        }

        // Convert inline style blocks to inline CSS on the HTML content.
        $content = $this->InlineCss->convert($content);
        $this->getView()->assign('content', $content);

        return;
    }

    /**
     * Checks if a string contains HTML or not.
     *
     * @param string $str String content
     * @return bool
     */
    public function isHtml($str)
    {
        return $str !== strip_tags($str);
    }
}
