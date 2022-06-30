<?php

declare(strict_types=1);

namespace Drupal\Tests\icon_bundle_api\Unit;

use Drupal\icon_bundle_api\IconBundleManager;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Tests\UnitTestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @internal
 * @coversNothing
 */
final class IconBundleManagerTest extends UnitTestCase {
  use ImplementsInterfaceTrait;

  /**
   * @coversNothing
   */
  public function testImplementsPluginManagerInterface(): void {
    static::assertImplementsInterface(PluginManagerInterface::class, IconBundleManager::class);
  }

}
