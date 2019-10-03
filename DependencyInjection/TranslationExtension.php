<?php

/*
 * Copyright (C) 2019 Akira Kurozumi <info@a-zumi.net>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

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

        if (!file_exists($dir)) {
            ruturn;
        }

        foreach ($extensionConfigs['framework'] as $key => $value) {
            if (isset($value["translator"]["paths"])) {
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

        $extensionConfigsRefl->setValue($container, $extensionConfigs);
    }
}