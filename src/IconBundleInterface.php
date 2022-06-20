<?php declare(strict_types=1);

/*
 * This file is part of ptomulik/icon_bundle_api.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Drupal\icon_bundle_api;

use Drupal\Component\Plugin\PluginInspectionInterface;

interface IconBundleInterface extends PluginInspectionInterface
{
    /**
     * Returns the label.
     */
    public function getLabel();

    /**
     * Returns the description.
     */
    public function getDescription();

    /**
     * Returns the config route.
     */
    public function getConfigRoute();

    /**
     * Returns the type of form element used to pick icons provided by bundle.
     */
    public function getIconPicker();

    /**
     * Returns the type of render element used to render icons provided by bundle.
     */
    public function getIconElement();
}
