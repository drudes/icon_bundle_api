<?php declare(strict_types=1);

/*
 * This file is part of ptomulik/icon_bundle_api.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Drupal\Tests\icon_bundle_api\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * @internal
 * @coversNothing
 */
final class IconBundleApiTest extends BrowserTestBase
{
    /**
     * Modules to install.
     *
     * @var array
     */
    protected static $modules = ['icon_bundle_api'];

    /**
     * Default theme.
     * https://www.drupal.org/node/3083055.
     *
     * @var string
     */
    protected $defaultTheme = 'stark';

    /**
     * A simple user.
     *
     * @var \Drupal\user\Entity\User;
     */
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->drupalCreateUser([
            'access administration pages',
        ]);
    }

    public function testIconBundleApiConfigLinkExists(): void
    {
        $this->drupalLogin($this->user);
        $this->drupalGet('admin/config');
        $this->assertSession()->linkExists('Icon Bundles');
    }

    public function testIconBundlesOverviewPageExists(): void
    {
        $this->drupalLogin($this->user);
        $this->drupalGet('admin/config/icons/overview');
        $this->assertSession()->statusCodeEquals(200);
        $this->assertSession()->pageTextContains('There are no Icon bundles enabled.');
    }
}
