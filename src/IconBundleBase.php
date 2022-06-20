<?php declare(strict_types=1);

/*
 * This file is part of ptomulik/icon_bundle_api.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Drupal\icon_bundle_api;

use Drupal\Component\Plugin\PluginBase;

class IconBundleBase extends PluginBase implements IconBundleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->pluginDefinition['label'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->pluginDefinition['description'];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigRoute()
    {
        return $this->pluginDefinition['config_route'];
    }

    /**
     * {@inheritdoc}
     */
    public function getIconPicker()
    {
        return $this->pluginDefinition['icon_picker'];
    }

    /**
     * {@inheritdoc}
     */
    public function getIconElement()
    {
        return $this->pluginDefinition['icon_element'];
    }
}
