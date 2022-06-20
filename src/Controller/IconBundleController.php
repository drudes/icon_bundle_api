<?php declare(strict_types=1);

/*
 * This file is part of ptomulik/icon_bundle_api.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Drupal\icon_bundle_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\icon_bundle_api\IconBundleManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Renders plugins of Icons.
 */
class IconBundleController extends ControllerBase
{
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
    public function __construct(IconBundleManager $iconBundleManager)
    {
        $this->iconBundleManager = $iconBundleManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static($container->get('plugin.manager.icon_bundle'));
    }

    /**
     * Renders the list of plugins for icon bundles.
     */
    public function plugins(): array
    {
        $bundles = $this->iconBundleManager->getDefinitions();

        $data = [];

        foreach ($bundles as $bundle) {
            $data[$bundle['id']] = $this->buildRow($bundle);
        }

        $form['overview'] = [
            '#theme'  => 'table',
            '#header' => $this->buildHeader(),
            '#rows'   => $data,
            '#empty'  => $this->t('There are no Icon bundles enabled.'),
        ];

        return $form;
    }

    /**
     * Builds the header row for the plugin.
     */
    public function buildHeader(): array
    {
        return [
            $this->t('Bundle Name'),
            $this->t('Bundle Machine Name'),
            $this->t('Operations'),
        ];
    }

    /**
     * Builds the row for the icon bundle plugin.
     *
     * @param \Drupal\icon_bundle_api\IconBundleManager $bundle The plugin definition
     */
    public function buildRow($bundle): array
    {
        return [
            'bundle' => [
                'data' => [
                    '#type'   => 'markup',
                    '#prefix' => '<b>'.$bundle['label'].'</b>',
                    '#suffix' => '<div class="icon-bundle-description">'.$bundle['description'].'</div>',
                ],
            ],
            'bundle_machine' => [
                'data' => [
                    '#type'   => 'markup',
                    '#prefix' => '<span>'.$bundle['id'].'</span>',
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
