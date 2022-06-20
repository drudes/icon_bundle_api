<?php declare(strict_types=1);

namespace Drupal\icon_bundle_api\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Icons bundle item annotation object.
 *
 * @see Drupal\icon_bundle_api\IconBundleManager
 * @see plugin_api
 *
 * @Annotation
 */
class IconBundle extends Plugin
{
    /**
     * The plugin machine name.
     *
     * @var string
     */
    public $id;

    /**
     * The label of the plugin.
     *
     * @var \Drupal\Core\Annotation\Translation
     *
     * @ingroup plugin_translatable
     */
    public $label;

    /**
     * The description of the plugin.
     *
     * @var \Drupal\Core\Annotation\Translation
     *
     * @ingroup plugin_translatable
     */
    public $description;

    /**
     * The route of the bundle config form.
     *
     * @var string
     */
    public $config_route;

    /**
     * The type of render element to be used by forms to pick icons provided by bundle.
     *
     * @var string
     */
    public $icon_picker;

    /**
     * The type of render element for icons provided by the bundle.
     *
     * @var string
     */
    public $icon_element;
}
