<?php

declare(strict_types=1);

namespace Drupal\icon_bundle_api\Element;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Details;

/**
 * @RenderElement("icon_picker")
 *
 * @phpstan-type SelectOptions array<string,\Drupal\Core\StringTranslation\TranslatableMarkup>
 *
 * @phpstan-type BundleArray array{
 *    '#type': 'select',
 *    '#name': string,
 *    '#default_value': string,
 *    '#options': SelectOptions,
 *    '#ajax': array{
 *      callback: callable,
 *      event: 'change',
 *      'wrapper': string,
 *    },
 *    '#title': \Drupal\Core\StringTranslation\TranslatableMarkup,
 *    '#description': \Drupal\Core\StringTranslation\TranslatableMarkup,
 *    '#empty_option': \Drupal\Core\StringTranslation\TranslatableMarkup,
 * }
 *
 * @phpstan-type IconSpecArray array{
 *    '#type': string,
 *    '#name': string,
 *    '#default_value': array{},
 *    '#prefix': string,
 *    '#suffix': string,
 *    '#states': array{
 *      invisible: array{
 *        array{string: array{value:''}},
 *      },
 *    },
 * }
 *
 * @phpstan-type ProcessIconPickerElement array{
 *  '#parents': string[],
 *  '#bundle'?: array{
 *    '#default_value'?: string,
 *    '#parents': string[],
 *  },
 *  '#icon_spec'?: array{
 *    '#default_value'?: array{},
 *    '#prefix'?: string,
 *    '#suffix'?: string,
 *    '#parents': string[],
 *  },
 * }
 *
 * @phpstan-type ProcessIconPickerRetval array{
 *  '#parents': string[],
 *  '#bundle'?: array{
 *    '#default_value'?: string,
 *    '#parents'?: string[],
 *  },
 *  '#icon_spec'?: array{
 *    '#default_value'?: array{},
 *    '#prefix'?: string,
 *    '#suffix'?: string,
 *    '#parents'?: string[],
 *  },
 *  '#access': bool,
 *  '#title': \Drupal\Core\StringTranslation\TranslatableMarkup,
 *  '#tree': bool,
 *  bundle: BundleArray,
 *  icon_spec: IconSpecArray,
 * }
 *
 * @phpstan-type GetDefaultValuesElement array{
 *  '#bundle'?: array{'#default_value'?: string},
 *  '#icon_spec'?: array{'#default_value'?: array{}},
 * }
 *
 * @phpstan-type GetDefaultValuesRetval array{
 *  bundle: string,
 *  icon_spec: array{}
 * }
 *
 * @phpstan-type ValueCallbackElement GetDefaultValuesElement
 * @phpstan-type ValueCallbackInput false|array{}
 * @phpstan-type ValueCallbackRetval GetDefaultValuesRetval|array{}
 *
 * @phpstan-type IconBundleOptionsBundleDefinition array{label:\Drupal\Core\StringTranslation\TranslatableMarkup}
 * @phpstan-type IconBundleOptionsBundleDefinitions array<string,IconBundleOptionsBundleDefinition>
 */
class IconPicker extends Details {

  /**
   * {@inheritdoc}
   *
   * @phpstan-return array<string,mixed>
   */
  public function getInfo(): array {
    $class = static::class;

    $info = parent::getInfo();
    $info['#input'] = TRUE;
    $info['#open'] = TRUE;
    // important!
    unset($info['#value']);
    $info['#process'][] = [$class, 'processIconPicker'];
    $info['#value_callback'] = [$class, 'valueCallback'];

    return $info;
  }

  /**
   * @phpstan-param ProcessIconPickerElement $element
   * @phpstan-return ProcessIconPickerRetval
   */
  public static function processIconPicker(array $element, FormStateInterface $form_state): array {
    $access = \Drupal::currentUser()->hasPermission('administer icons');
    $element += [
      '#access' => $access,
      '#title'  => t('Icon'),
      '#tree'   => TRUE,
    ];

    $icon_bundles = \Drupal::service('plugin.manager.icon_bundle')->getDefinitions();

    $icon_spec_wrapper_id = implode('-', $element['#parents']) . '-icon_spec-wrapper';

    $defaults = static::getDefaultValues($element);
    $inputs = $form_state->getValue($element['#parents']);
    $values = $inputs + $defaults;
    $names = static::getNestedElementNames($element);

    $element['bundle'] = [
      '#type'          => 'select',
      '#name'          => $names['bundle'],
      '#default_value' => $defaults['bundle'],
      '#options'       => self::iconBundleOptions($icon_bundles),
      '#ajax'          => [
        'callback' => [self::class, 'updateIconSpec'],
        'event'    => 'change',
        'wrapper'  => $icon_spec_wrapper_id,
      ],
    ] + ($element['#bundle'] ?? []) + [
      '#title'        => t('Icon Bundle'),
      '#description'  => t('Choose the icon bundle to display the icons using the autocomplete.'),
      '#empty_option' => t('None'),
    ];

    $element['icon_spec'] = [
      '#type'          => $icon_bundles[$values['bundle']]['icon_picker'] ?? 'container',
      '#name'          => $names['icon_spec'],
      '#default_value' => $defaults['icon_spec'],
      '#prefix'        => ($element['#icon_spec']['#prefix'] ?? '') . '<div id="' . $icon_spec_wrapper_id . '">',
      '#suffix'        => '</div>' . ($element['#icon_spec']['#suffix'] ?? ''),
      '#states'        => [
        'invisible' => [
                  [':input[name="' . $names['bundle'] . '"]' => ['value' => '']],
        ],
      ],
    ] + ($element['#icon_spec'] ?? []);

    return $element;
  }

  /**
   * @param mixed $input
   * @return mixed
   *
   * @phpstan-param ValueCallbackElement $element
   * @phpstan-param ValueCallbackInput $input
   * @phpstan-return ValueCallbackRetval
   */
  public static function valueCallback(array $element, $input) {
    if (FALSE === $input) {
      return static::getDefaultValues($element);
    }

    return $input;
  }

  /**
   * Returns the 'Icon Spec' element.
   *
   * @phpstan-param array<array-key,mixed> $form
   * @phpstan-return mixed
   */
  public static function updateIconSpec(array &$form, FormStateInterface $form_state) {
    // $triggering_element is a first-level sub-element of the $element ($element['bundle'] in fact)
    $triggering_element = $form_state->getTriggeringElement();

    // Acces the $element, the one returned from $this->formElement().
    $element_parents = array_slice($triggering_element['#array_parents'] ?? [], 0, -1);
    $element = NestedArray::getValue($form, $element_parents);

    return $element['icon_spec'];
  }

  /**
   * Returns an array of options for $element['bundle']['#options'].
   *
   * @phpstan-param IconBundleOptionsBundleDefinitions $bundle_definitions
   * @phpstan-return SelectOptions
   */
  protected static function iconBundleOptions(array $bundle_definitions): array {
    $options = [];
    foreach ($bundle_definitions as $key => $bundle_definition) {
      $options[$key] = $bundle_definition['label'];
    }

    return $options;
  }

  /**
   * @phpstan-param GetDefaultValuesElement $element
   * @phpstan-return GetDefaultValuesRetval
   */
  protected static function getDefaultValues(array $element): array {
    return [
      'bundle'    => $element['#bundle']['#default_value'] ?? '',
      'icon_spec' => $element['#icon_spec']['#default_value'] ?? [],
    ];
  }

  /**
   * @phpstan-param array{'#parents': string[]} $element
   * @phpstan-return array<'bundle'|'icon_spec', string>
   */
  protected static function getNestedElementNames(array $element): array {
    $names = [];
    foreach (['bundle', 'icon_spec'] as $key) {
      $names[$key] = static::nestedElementName($element['#parents'], $key);
    }

    return $names;
  }

  /**
   * @phpstan-param string[] $parents
   */
  protected static function nestedElementName(array $parents, string $child): string {
    if (empty($parents)) {
      return $child;
    }

    $parents[] = $child;

    $root = array_shift($parents);

    return $root . '[' . implode('][', $parents) . ']';
  }

}
