<?php

namespace Omouren\ExternalVarDumperBundle\DependencyInjection\Compiler;

use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ReplaceServerConnectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $extension = $container->getExtension('omouren_external_var_dumper');

        if ($extension->isEnabled()) {
            $container->getDefinition('data_collector.dump')
                ->replaceArgument(4, new Reference('omouren_external_var_dumper.var_dumper.server_connection'));
        }
    }
}