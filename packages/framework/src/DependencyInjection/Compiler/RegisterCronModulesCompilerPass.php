<?php

namespace Shopsys\FrameworkBundle\DependencyInjection\Compiler;

use Shopsys\FrameworkBundle\Component\Cron\Config\CronConfig;
use Shopsys\FrameworkBundle\Component\Cron\Config\CronModuleConfig;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterCronModulesCompilerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $cronConfigDefinition = $container->findDefinition(CronConfig::class);

        $taggedServiceIds = $container->findTaggedServiceIds('shopsys.cron');
        foreach ($taggedServiceIds as $serviceId => $tags) {
            foreach ($tags as $tag) {
                $cronConfigDefinition->addMethodCall(
                    'registerCronModuleInstance',
                    [
                        new Reference($serviceId),
                        $serviceId,
                        $tag['hours'],
                        $tag['minutes'],
                        $tag['instanceName'] ?? CronModuleConfig::DEFAULT_INSTANCE_NAME,
                        $tag['readableName'] ?? null,
                    ]
                );
            }
        }
    }
}
