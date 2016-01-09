<?php

/*
 * This file is part of the SgomezDebugSwiftMailerBundle.
 *
 * (c) Sergio Gómez <decano@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\DebugSwiftMailerBundle\ServiceContainer;


use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

class Extension implements ExtensionInterface
{
    const SWIFTMAILER_ID = 'swiftmailer_extension';

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'swiftmailer_extension';
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * Loads a specific configuration.
     *
     * @param array $config An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadServices($container);
        $this->loadContextInitializer($container);
    }

    private function loadServices(ContainerBuilder $container)
    {
        $definition = new Definition('Sgomez\DebugSwiftMailerBundle\Utils\SwiftMailerManager', [
            new Reference('symfony2_extension.kernel')
        ]);
        $container->setDefinition(self::SWIFTMAILER_ID, $definition);
    }


    private function loadContextInitializer(ContainerBuilder $container)
    {
        $definition = new Definition('Sgomez\DebugSwiftMailerBundle\Behat\Initializer\ContextInitializer', [
           new Reference(self::SWIFTMAILER_ID)
        ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);
        $container->setDefinition(self::SWIFTMAILER_ID.'.context_initializer', $definition);
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
    }
}
