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
        $prisma = [
            'operation' => [
                'collection' => 'olla.api_collection_action',
                'create' => 'olla.api_create_action',
                'update' => 'olla.api_update_action',
                'delete' => 'olla.api_delete_action',
                'item' => 'olla.api_item_action',

            ],
            'admin' => [
                'collection' => 'olla.admin_collection_action',
                'create' => 'olla.admin_create_action',
                'update' => 'olla.admin_update_action',
                'delete' => 'olla.admin_delete_action',
                'item' => 'olla.admin_item_action',
                'item_form' => 'olla.admin_item_form_action'
            ],
            'dirs' => $this->getDirs($container)
        ];
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'olla_prisma':
                $container->prependExtensionConfig($name, $prisma);
                break;
                case 'olla_flow':
                case 'olla_theme':
            }
        }
    }

    private function getDirs(ContainerBuilder $container): array
    {
        $admins = [];
        $frontends = [];
        $apis = [];
        $resources = [];
        foreach ($container->getParameter('kernel.bundles_metadata') as $bundle) {
      
            $basedir = $bundle['path'];
            $admins[] = $basedir.'/Operations/Admin';
            $frontends[] = $basedir.'/Operations/Frontend';
            $apis[] = $basedir.'/Operations/Api';
            $resources[] = $basedir.'/Resource';
        }
        return [
            'admin_module' => $admins,
            'frontend_module' => $frontends,
            'api_module' => $apis,
            'resource_module' => $resources
        ];
    }




    public function load(array $configs, ContainerBuilder $container)
    { 
        $this->reconfig($configs, $container);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('controller.xml');
        $loader->load('router.xml');
        $loader->load('operation.xml');
    }
    private function reconfig(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

    }
}
