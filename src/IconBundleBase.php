<?php

declare(strict_types=1);

namespace Drupal\icon_bundle_api;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 */
class IconBundleBase extends PluginBase implements IconBundleInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel(): TranslatableMarkup {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription(): TranslatableMarkup {
    return $this->pluginDefinition['description'];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigRoute(): string {
    return $this->pluginDefinition['config_route'];
  }

  /**
   * {@inheritdoc}
   */
  public function getIconPicker(): string {
    return $this->pluginDefinition['icon_picker'];
  }

  /**
   * {@inheritdoc}
   */
  public function getIconElement(): string {
    return $this->pluginDefinition['icon_element'];
  }

}
