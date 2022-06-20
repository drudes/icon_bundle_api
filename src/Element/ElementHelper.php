<?php declare(strict_types=1);

namespace Drupal\icon_bundle_api\Element;

use Drupal\Core\Form\FormStateInterface;

class ElementHelper
{
    /**
     * Provided ``$parents=['foo', bar']`` and ``$name='gez'`` it returns ``'foo[bar][gez]'``.
     */
    public static function nestedElementName(array $parents, string $name): string
    {
        if (0 === count($parents)) {
            return $name;
        }

        $root = array_shift($parents);
        $keys = array_merge($parents, [$name]);

        return $root.'['.implode('][', $keys).']';
    }

    public static function nestedElementValue(FormStateInterface $form_state, array $parents, string $name, $default = null)
    {
        if (0 === count($parents)) {
            return $form_state->getValue($name, $default);
        }

        $parents[] = $name;

        return $form_state->getValue($parents, $default);
    }
}
