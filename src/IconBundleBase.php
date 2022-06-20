<?php declare(strict_types=1);

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
