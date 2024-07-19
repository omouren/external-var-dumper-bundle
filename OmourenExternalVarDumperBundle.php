<?php

namespace Omouren\ExternalVarDumperBundle;

use Omouren\ExternalVarDumperBundle\DependencyInjection\Compiler\ReplaceServerConnectionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class OmourenExternalVarDumperBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReplaceServerConnectionCompilerPass());
    }
}
