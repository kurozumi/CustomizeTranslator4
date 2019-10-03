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

        $dir = $container->getParameter('kernel.project_dir') . '/app/Customize/Resource/locale';

        foreach ($extensionConfigs['framework'] as $key => $value) {
            if (isset($value["translator"]["paths"])) {
                if (file_exists($dir)) {
                    foreach ($value["translator"]["paths"] as $path) {
                        if ($path == "%kernel.project_dir%/src/Eccube/Resource/locale/") {
                            $paths = $extensionConfigs['framework'][$key]['translator']["paths"];
                            array_push($paths, $dir);
                            $extensionConfigs['framework'][$key]['translator']["paths"] = $paths;
                            break;
                        }
                    }
                }
            }
        }

        $extensionConfigsRefl->setValue($container, $extensionConfigs);
    }
}