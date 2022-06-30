<?php

declare(strict_types=1);

namespace Drupal\icon_bundle_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\icon_bundle_api\IconBundleManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Renders plugins of Icons.
 *
 * @phpstan-import-type IconBundleDefinition from \Drupal\icon_bundle_api\IconBundleInterface
 *
 * @phpstan-type OperationsLinks array{
 *  edit: array{
 *    title: \Drupal\Core\StringTranslation\TranslatableMarkup,
 *    url: \Drupal\Core\Url,
 *  }
 * }
 *
 * @phpstan-type TableRowArray array{
 *  bundle: array{
 *    data: array{
 *      '#markup': string,
 *    }
 *  },
 *  bundle_machine: array{
 *    data: array{
 *      '#markup': string,
 *    }
 *  },
 *  operations: array{
 *    data: array{
 *      '#type': string,
 *      '#links': OperationsLinks,
 *    }
 *  },
 * }
 *
 * @phpstan-type TableHeaderArray array{
 *  0: \Drupal\Core\StringTranslation\TranslatableMarkup,
 *  1: \Drupal\Core\StringTranslation\TranslatableMarkup,
 *  2: \Drupal\Core\StringTranslation\TranslatableMarkup,
 * }
 */
final class IconBundleController extends ControllerBase {
  /**
   * The icons bundle manager.
   *
   * @var \Drupal\icon_bundle_api\IconBundleManager
   */
  private $iconBundleManager;

  /**
   * IconBundleController constructor.
   *
   * @param \Drupal\icon_bundle_api\IconBundleManager $iconBundleManager
   */
  public function __construct(IconBundleManager $iconBundleManager) {
    $this->iconBundleManager = $iconBundleManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self($container->get('plugin.manager.icon_bundle'));
  }

  /**
   * Renders the list of plugins for icon bundles.
   *
   * @phpstan-return array{
   *    overview: array{
   *      '#theme': string,
   *      '#header': TableHeaderArray,
   *      '#rows': array<string, TableRowArray>,
   *      '#empty': \Drupal\Core\StringTranslation\TranslatableMarkup
   *    }
   *  }
   */
  public function plugins(): array {
    $form = [
      'overview' => [
        '#theme'  => 'table',
        '#header' => $this->buildHeader(),
        '#rows'   => $this->buildRows(),
        '#empty'  => $this->t('There are no Icon bundles enabled.'),
      ],
    ];

    return $form;
  }

  /**
   * Builds the header row for the plugin.
   *
   * @phpstan-return TableHeaderArray
   */
  private function buildHeader(): array {
    return [
      $this->t('Bundle Name'),
      $this->t('Bundle Machine Name'),
      $this->t('Operations'),
    ];
  }

  /**
   * Builds all the rows for the icon bundle plugins.
   *
   * @phpstan-return array<string, TableRowArray>
   */
  private function buildRows(): array {
    $bundles = $this->iconBundleManager->getDefinitions();

    $data = [];

    foreach ($bundles as $bundle) {
      $data[$bundle['id']] = $this->buildRow($bundle);
    }

    return $data;
  }

  /**
   * Builds the row for the icon bundle plugin.
   *
   * @phpstan-param IconBundleDefinition $bundle
   *   The plugin definition.
   *
   * @phpstan-return TableRowArray
   */
  private function buildRow(array $bundle): array {
    $markup = [
      'id' => '<span>' . $bundle['id'] . '</span>',
      'label' => '<b>' . $bundle['label'] . '</b>',
      'description' => '<div class="icon-bundle-description">' . $bundle['description'] . '</div>',
    ];

    return [
      'bundle' => [
        'data' => [
          '#markup' => $markup['label'] . $markup['description'],
        ],
      ],
      'bundle_machine' => [
        'data' => [
          '#markup' => $markup['id'],
        ],
      ],
      'operations' => [
        'data' => [
          '#type'  => 'operations',
          '#links' => [
            'edit' => [
              'title' => $this->t('Configure'),
              'url'   => Url::fromRoute($bundle['config_route']),
            ],
          ],
        ],
      ],
    ];
  }

}
