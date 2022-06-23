<?php

declare(strict_types=1);

namespace Drupal\icon_bundle_api;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\icon_bundle_api\Annotation\IconBundle;

/**
 * @phpstan-import-type IconBundleDefinition from IconBundleInterface
 *
 * @method array<string,IconBundleDefinition> getDefinitions()
 */
class IconBundleManager extends DefaultPluginManager implements IconBundleManagerInterface {

  /**
   * Construct a new IconBundleManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *                                     keyed by the corresponding namespace to look for plugin
   *                                     implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   *
   * @phpstan-param \Traversable<string,string> $namespaces
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
}
