<?php declare(strict_types=1);

namespace Drupal\icon_bundle_api\Element;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\Element\Details;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\icon_bundle_api\IconBundleManager;

/**
 * @RenderElement("icon_picker")
 */
class IconPicker extends Details
{
    /**
     * {@inheritdoc}
     */
    public function getInfo()
    {
        $class = static::class;

        $info = parent::getInfo();
        $info['#input'] = true;
        $info['#open'] = true;
        unset($info['#value']);  // important!
        $info['#process'][] = [$class, 'processIconPicker'];
        $info['#value_callback'] = [$class, 'valueCallback'];

        return $info;
    }

    public static function processIconPicker($element, FormStateInterface $form_state, &$form)
    {
        $access = \Drupal::currentUser()->hasPermission('administer icons');
        $element += [
            '#access' => $access,
            '#title'  => t('Icon'),
            '#tree'   => true,
        ];

        $icon_bundles = IconBundleManager::getIconBundles();

        $icon_spec_wrapper_id = implode('-', $element['#parents']).'-icon_spec-wrapper';

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
            '#prefix'        => ($element['#icon_spec']['#prefix'] ?? '').'<div id="'.$icon_spec_wrapper_id.'">',
            '#suffix'        => '</div>'.($element['#icon_spec']['#suffix'] ?? ''),
            '#states'        => [
                'invisible' => [
                    [':input[name="'.$names['bundle'].'"]' => ['value' => '']],
                ],
            ],
        ] + ($element['#icon_spec'] ?? []);

        return $element;
    }

    public static function valueCallback(&$element, $input, FormStateInterface $form_state)
    {
        if (false === $input) {
            return static::getDefaultValues($element);
        }

        return $input;
    }

    /**
     * Returns the 'Icon Spec' element.
     */
    public static function updateIconSpec(array &$form, FormStateInterface $form_state): array
    {
        // $triggering_element is a first-level sub-element of the $element ($element['bundle'] in fact)
        $triggering_element = $form_state->getTriggeringElement();

        // acces the $element, the one returned from $this->formElement().
        $element_parents = array_slice($triggering_element['#array_parents'] ?? [], 0, -1);
        $element = NestedArray::getValue($form, $element_parents);

        return $element['icon_spec'];
    }

    /**
     * Returns an array of options for $element['bundle']['#options'].
     */
    protected static function iconBundleOptions(array $bundle_definitions): array
    {
        $options = [];
        foreach ($bundle_definitions as $key => $bundle_definition) {
            $options[$key] = $bundle_definition['label'];
        }

        return $options;
    }

    protected static function getDefaultValues(array $element): array
    {
        return [
            'bundle'    => $element['#bundle']['#default_value'] ?? '',
            'icon_spec' => $element['#icon_spec']['#default_value'] ?? [],
        ];
    }

    protected static function getNestedElementNames(array $element): array
    {
        $names = [];
        foreach (['bundle', 'icon_spec'] as $key) {
            $names[$key] = ElementHelper::nestedElementName($element['#parents'], $key);
        }

        return $names;
    }
}
