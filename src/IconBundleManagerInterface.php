<?php

declare(strict_types=1);

namespace Drupal\icon_bundle_api;

/**
 *
 */
interface IconBundleManagerInterface {

  /**
   *
   */
  public static function getIconBundleManagerService();

  /**
   *
   */
  public static function getIconBundles();

  /**
   *
   */
  public static function getIconBundle(string $name);

}
