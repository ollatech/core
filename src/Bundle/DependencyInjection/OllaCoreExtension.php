<?php

namespace Olla\Core\Bundle\DependencyInjection;



use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final class OllaCoreExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'olla_core':
                $container->prependExtensionConfig($name, [
                    'middlewares' => [
                        'api' => 'olla.api_middleware',
                        'frontend' => 'olla.frontend_middleware',
                        'admin' => 'olla.admin_middleware',
                        'tool' => 'olla.tool_middleware'
                    ],
                    'negotiations' => [
                        'api' => 'olla.api_negotiation',
                        'frontend' => 'olla.frontend_negotiation',
                        'admin' => 'olla.admin_negotiation',
                        'tool' => 'olla.tool_negotiation'
                    ],
                    'themes' => [
                        'api' => 'olla.api_theme',
                        'frontend' => 'olla.frontend_theme',
                        'admin' => 'olla.admin_theme',
                        'tool' => 'olla.tool_theme'
                    ],
                    'theme' => [
                        'api' => 'api',
                        'frontend' => 'frontend',
                        'admin' => 'admin',
                        'tool' => 'tool'
                    ]
                ]);
                break;
                case 'olla_prisma':
                if (!isset($bundles['OllaPlatformBundle'])) {
                    $container->prependExtensionConfig($name, [
                        'operations' => [
                            'api' => [
                                'search' => 'olla_platform.api_collection_action',
                                'collection' => 'olla_platform.api_collection_action',
                                'create' => 'olla_platform.api_create_action',
                                'update' => 'olla_platform.api_update_action',
                                'delete' => 'olla_platform.api_delete_action',
                                'item' => 'olla_platform.api_item_action',
                            ],
                            'admin' => [
                                'collection' => 'olla_platform.admin_collection_action',
                                'create' => 'olla_platform.admin_create_action',
                                'update' => 'olla_platform.admin_update_action',
                                'delete' => 'olla_platform.admin_delete_action',
                                'item' => 'olla_platform.admin_item_action',
                                'item_form' => 'olla_platform.admin_item_form_action'
                            ],
                        ],
                        'dirs' => $this->getDirs($container)
                    ]);
                } else {
                    $container->prependExtensionConfig($name, [
                        'operations' => [
                            'api' => [
                                'search' => 'olla.dummy_action',
                                'collection' => 'olla.dummy_action',
                                'create' => 'olla.dummy_action',
                                'update' => 'olla.dummy_action',
                                'delete' => 'olla.dummy_action',
                                'item' => 'olla.dummy_action',
                            ],
                            'admin' => [
                                'collection' => 'olla.dummy_action',
                                'create' => 'olla.dummy_action',
                                'update' => 'olla.dummy_action',
                                'delete' => 'olla.dummy_action',
                                'item' => 'olla.dummy_action',
                                'item_form' => 'olla.dummy_action'
                            ],
                        ],
                        'dirs' => $this->getDirs($container)
                    ]);
                }
                break;
                case 'olla_theme':
                break;
            }
        }



    }

    private function getDirs(ContainerBuilder $container): array
    {
        $admins = [];
        $frontends = [];
        $apis = [];
        $tools = [];
        $resources = [];
        foreach ($container->getParameter('kernel.bundles_metadata') as $bundle) {
            $basedir = $bundle['path'];
            $admins[] = $basedir.'/Operations/Admin';
            $frontends[] = $basedir.'/Operations/Frontend';
            $apis[] = $basedir.'/Operations/Api';
            $tools[] = $basedir.'/Operations/Tool';
            $resources[] = $basedir.'/Resource';
        }
        return [
            'admin_module' => $admins,
            'frontend_module' => $frontends,
            'api_module' => $apis,
            'tool_module' => $tools,
            'resource_module' => $resources
        ];
    }

    public function load(array $configs, ContainerBuilder $container)
    { 

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('controller.xml');
        $loader->load('router.xml');
        $loader->load('operation.xml');
        $loader->load('middleware.xml');
        $loader->load('negotiation.xml');
        $loader->load('theme.xml');
        $this->reconfig($configs, $container);
    }
    private function reconfig(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $controllers = [
            'api' => 'olla.api_controller',
            'frontend' => 'olla.frontend_controller',
            'admin' => 'olla.admin_controller',
            'tool' => 'olla.tool_controller'
        ];
        if(isset($config['controllers'])) {
            $controllers = array_merge($controllers, $config['controllers']);
        }
        $container->setParameter('olla.controllers', $controllers);
        $formats = [
            'api' => 'json',
            'frontend' => 'html',
            'admin' => 'html',
            'tool' => 'html'
        ];
        if(isset($config['formats'])) {
            $formats = array_merge($formats, $config['formats']);
        }
        $container->setParameter('olla.formats', $formats);

        $prefixes = [
            'api' => '/api',
            'frontend' => '/',
            'admin' => '/admin',
            'tool' => '/'
        ];
        if(isset($config['prefixes'])) {
            $prefixes = array_merge($prefixes, $config['prefixes']);
        }
        $container->setParameter('olla.prefixes', $prefixes);

        if(isset($config['middlewares'])) {
            $settings = $config['middlewares'];
            foreach ($settings as $key => $value) {
                $paramKey = 'olla.'.$key.'_middleware';
                $container->setParameter($paramKey, $value);
            }
        }
        if(isset($config['negotiations'])) {
            $settings = $config['negotiations'];
            foreach ($settings as $key => $value) {
                $paramKey = 'olla.'.$key.'_negotiation';
                $container->setParameter($paramKey, $value);
            }
        }
        if(isset($config['themes'])) {
            $settings = $config['themes'];
            foreach ($settings as $key => $value) {
                $paramKey = 'olla.'.$key.'_theme';
                $container->setParameter($paramKey, $value);
            }
        }
        if(isset($config['theme'])) {
            $settings = $config['theme'];
            $container->setParameter('olla.active_theme', $settings);
            foreach ($settings as $key => $value) {
                $paramKey = 'olla.'.$key.'_theme_name';
                $container->setParameter($paramKey, $value);
            }
        }
    }
}
