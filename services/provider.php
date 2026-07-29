<?php
/**
 * @package     JoomCode
 * @subpackage  plg_content_joomcode
 *
 * @copyright   Copyright (C) 2026 JoomTheme. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use JoomTheme\Plugin\Content\Joomcode\Extension\Joomcode;

return new class () implements ServiceProviderInterface {
    /**
     * Registers the plugin extension in Joomla's dependency injection container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     */
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            static function (Container $container) {
                $config     = (array) PluginHelper::getPlugin('content', 'joomcode');
                $dispatcher = $container->get(DispatcherInterface::class);
                $plugin     = new Joomcode($dispatcher, $config);

                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};
