<?php
/**
 * @package     JoomCode
 * @subpackage  plg_content_joomcode
 *
 * @copyright   Copyright (C) 2026 JoomTheme. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace JoomTheme\Plugin\Content\Joomcode\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

final class Joomcode extends CMSPlugin implements SubscriberInterface
{
    /**
     * Load language strings automatically.
     *
     * @var boolean
     */
    protected $autoloadLanguage = true;

    /**
     * Tracks whether assets and script options were already registered.
     *
     * @var boolean
     */
    private bool $assetsLoaded = false;

    /**
     * Returns the Joomla events handled by this plugin.
     *
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'prepareCodeBlocks',
        ];
    }

    /**
     * Marks preformatted blocks and loads assets only when a block exists.
     *
     * @param   ContentPrepareEvent  $event  The content prepare event.
     *
     * @return  void
     */
    public function prepareCodeBlocks(ContentPrepareEvent $event): void
    {
        $application = $this->getApplication();

        if (!$application->isClient('site')) {
            return;
        }

        $item = $event->getItem();

        if (!is_object($item) || !property_exists($item, 'text') || !is_string($item->text)) {
            return;
        }

        if (stripos($item->text, '<pre') === false) {
            return;
        }

        $processedText = preg_replace_callback(
            '~<pre\b([^>]*)>~i',
            static function (array $matches): string {
                if (preg_match('~\bdata-joomcode(?:\s*=|\s|$)~i', $matches[1]) === 1) {
                    return $matches[0];
                }

                return '<pre' . $matches[1] . ' data-joomcode="true">';
            },
            $item->text
        );

        if (is_string($processedText)) {
            $item->text = $processedText;
        }

        $this->loadAssets();
    }

    /**
     * Registers and enables the extension assets through Web Asset Manager.
     *
     * @return void
     */
    private function loadAssets(): void
    {
        if ($this->assetsLoaded) {
            return;
        }

        $document = $this->getApplication()->getDocument();

        if (!$document instanceof HtmlDocument) {
            return;
        }

        $theme = (string) $this->params->get('theme', 'auto');

        if (!in_array($theme, ['auto', 'light', 'dark'], true)) {
            $theme = 'auto';
        }

        $fallbackLanguage = (string) $this->params->get('fallback_language', 'none');

        if (!in_array($fallbackLanguage, ['none', 'markup', 'php', 'css', 'javascript'], true)) {
            $fallbackLanguage = 'none';
        }

        $maxHeight = (int) $this->params->get('max_height', 0);
        $maxHeight = max(0, min(10000, $maxHeight));

        $assetManager = $document->getWebAssetManager();
        $assetManager->getRegistry()->addExtensionRegistryFile('plg_content_joomcode');
        $assetManager->useStyle('plg_content_joomcode.style');
        $assetManager->useScript('plg_content_joomcode.script');

        $document->addScriptOptions(
            'plg_content_joomcode',
            [
                'theme'             => $theme,
                'showLanguage'      => (bool) $this->params->get('show_language', 1),
                'showCopy'          => (bool) $this->params->get('show_copy', 1),
                'lineNumbers'       => (bool) $this->params->get('line_numbers', 0),
                'lineWrap'          => (bool) $this->params->get('line_wrap', 0),
                'maxHeight'         => $maxHeight,
                'fallbackLanguage'  => $fallbackLanguage,
                'copyLabel'         => Text::_('PLG_CONTENT_JOOMCODE_COPY'),
                'copiedLabel'       => Text::_('PLG_CONTENT_JOOMCODE_COPIED'),
                'copyErrorLabel'    => Text::_('PLG_CONTENT_JOOMCODE_COPY_ERROR'),
                'plainTextLabel'    => Text::_('PLG_CONTENT_JOOMCODE_LANGUAGE_PLAINTEXT'),
            ]
        );

        $this->assetsLoaded = true;
    }
}
