<?php

namespace Omouren\ExternalVarDumperBundle;

use Omouren\ExternalVarDumperBundle\Event\ExternalVarDumpEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class OmourenExternalVarDumperBundle extends Bundle
{
}
