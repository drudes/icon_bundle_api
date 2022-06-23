<?php

declare(strict_types=1);

namespace Drupal\icon_bundle_api;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * @phpstan-type IconBundleDefinition array{
 *  id: string,
 *  label: \Drupal\Core\StringTranslation\TranslatableMarkup,
 *  description: \Drupal\Core\StringTranslation\TranslatableMarkup,
 *  config_route: string,
 *  icon_picker: string,
 *  icon_element: string,
 *  class: class-string,
 *  provider: string
 * }
 */
interface IconBundleInterface extends PluginInspectionInterface {

  /**
   * Returns the label.
   */
  public function getLabel(): TranslatableMarkup;

  /**
   * Returns the description.
   */
  public function getDescription(): TranslatableMarkup;

  /**
   * Returns the config route.
   */
  public function getConfigRoute(): string;

  /**
   * Returns the type of form element used to pick icons provided by bundle.
   */
  public function getIconPicker(): string;

  /**
   * Returns the type of render element used to render icons provided by bundle.
   */
  public function getIconElement(): string;

}
