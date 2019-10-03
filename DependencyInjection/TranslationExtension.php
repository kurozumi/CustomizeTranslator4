<?php


namespace Plugin\CustomizeTranslator4\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TranslationExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // TODO: Implement load() method.
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->configureTranslations($container);
    }

    /**
     * @param string $pluginDir
     */
    protected function configureTranslations(ContainerBuilder $container)
    {
        $extensionConfigsRefl = new \ReflectionProperty(ContainerBuilder::class, 'extensionConfigs');
        $extensionConfigsRefl->setAccessible(true);
        $extensionConfigs = $extensionConfigsRefl->getValue($container);

        $paths = $extensionConfigs['framework'][0]['translator']["paths"];

        $dir = $container->getParameter('kernel.project_dir') . '/app/Customize/Resource/locale';

        if (file_exists($dir)) {
            array_push($paths, $dir);
        }

        $extensionConfigs['framework'][0]['translator']["paths"] = $paths;

        $extensionConfigsRefl->setValue($container, $extensionConfigs);
    }
}