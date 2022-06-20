<?php declare(strict_types=1);

/*
 * This file is part of ptomulik/icon_bundle_api.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Drupal\icon_bundle_api;

interface IconBundleManagerInterface
{
    public static function getIconBundleManagerService();

    public static function getIconBundles();

    public static function getIconBundle(string $name);
}
