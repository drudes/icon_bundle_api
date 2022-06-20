<?php declare(strict_types=1);

/*
 * This file is part of ptomulik/icon_bundle_api.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Drupal\Tests\icon_bundle_api\Unit;

use Drupal\icon_bundle_api\IconBundleManager;
use Drupal\icon_bundle_api\IconBundleManagerInterface;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @internal
 * @coversNothing
 */
final class IconBundleManagerTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @coversNothing
     */
    public function testImplementsIconBundleManagerInterface(): void
    {
        $this->assertImplementsInterface(IconBundleManagerInterface::class, IconBundleManager::class);
    }
}
