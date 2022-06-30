<?php

declare(strict_types=1);

namespace Drupal\Tests\icon_bundle_api\Unit;

use Drupal\icon_bundle_api\IconBundleManager;
use Drupal\icon_bundle_api\IconBundleManagerInterface;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @internal
 * @coversNothing
 */
final class IconBundleManagerTest extends TestCase {
  use ImplementsInterfaceTrait;

  /**
   * @coversNothing
   */
  public function testImplementsIconBundleManagerInterface(): void {
    static::assertImplementsInterface(IconBundleManagerInterface::class, IconBundleManager::class);
  }

}
