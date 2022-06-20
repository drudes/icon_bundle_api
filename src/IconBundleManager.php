<?php declare(strict_types=1);

namespace Drupal\icon_bundle_api;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\icon_bundle_api\Annotation\IconBundle;

class IconBundleManager extends DefaultPluginManager implements IconBundleManagerInterface
{
    /**
     * Construct a new IconBundleManager object.
     *
     * @param \Traversable                                  $namespaces
     *                                                                      An object that implements \Traversable which contains the root paths
     *                                                                      keyed by the corresponding namespace to look for plugin
     *                                                                      implementations
     * @param \Drupal\Core\Cache\CacheBackendInterface      $cache_backend
     *                                                                      Cache backend instance to use
     * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
     *                                                                      The module handler to invoke the alter hook with
     */
    public function __construct(
        \Traversable $namespaces,
        CacheBackendInterface $cache_backend,
        ModuleHandlerInterface $module_handler,
    ) {
        parent::__construct(
            'Plugin/IconBundle',
            $namespaces,
            $module_handler,
            IconBundleInterface::class,
            IconBundle::class
        );

        $this->alterInfo('icon_bundle_info');
        $this->setCacheBackend($cache_backend, 'icon_bundle_plugins');
    }

    public static function getIconBundleManagerService()
    {
        return \Drupal::service('plugin.manager.icon_bundle');
    }

    /**
     * Returns the installed Icon bundles.
     */
    public static function getIconBundles()
    {
        $bundle_manager = self::getIconBundleManagerService();

        return $bundle_manager->getDefinitions();
    }

    public static function getIconBundle(string $name)
    {
        $bundles = self::getIconBundles();

        return $bundles[$name];
    }
}
