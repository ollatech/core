<?php

declare(strict_types=1);

namespace Olla\Core\Kernel;


use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\HttpKernel\Kernel as HttpKernel;


class AppKernel extends HttpKernel
{
    public const VERSION = '1.0.0-DEV';
    public const VERSION_ID = '10000';
    public const MAJOR_VERSION = '1';
    public const MINOR_VERSION = '0';
    public const RELEASE_VERSION = '0';
    public const EXTRA_VERSION = 'DEV';

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): array
    {
        $bundles = [
           
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test', 'test_cached'], true)) {
            
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerBaseClass(): string
    {
     

        return parent::getContainerBaseClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerLoader(ContainerInterface $container): LoaderInterface
    {
       

        $locator = new FileLocator($this, $this->getRootDir() . '/Resources');
        $resolver = new LoaderResolver([
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ]);

        return new DelegatingLoader($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');

        $file = $this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.local.yml';
        if (is_file($file)) {
            $loader->load($file);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        if ($this->isVagrantEnvironment()) {
            return '/dev/shm/olla/cache/' . $this->getEnvironment();
        }

        return dirname($this->getRootDir()) . '/var/cache/' . $this->getEnvironment();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        if ($this->isVagrantEnvironment()) {
            return '/dev/shm/olla/logs';
        }

        return dirname($this->getRootDir()) . '/var/logs';
    }

    /**
     * @return bool
     */
    protected function isVagrantEnvironment(): bool
    {
        return (getenv('HOME') === '/home/vagrant' || getenv('VAGRANT') === 'VAGRANT') && is_dir('/dev/shm');
    }
}
