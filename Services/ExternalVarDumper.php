<?php

namespace Omouren\ExternalVarDumperBundle\Services;

use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;
use Omouren\ExternalVarDumperBundle\Event\ExternalVarDumpEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class ExternalVarDumper
{
    private $eventDispatcher;

    private $enabled;

    private $cloner;

    private $kernelDebug;

    public function __construct(EventDispatcherInterface $eventDispatcher, $kernelDebug, $cloner, $enabled)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->kernelDebug = $kernelDebug;
        $this->cloner = $cloner;
        $this->enabled = $enabled;
    }

    public function handleInit()
    {
        if ($this->kernelDebug && $this->enabled) {
            VarDumper::setHandler(function($var) {
                $handler = function ($var) {
                    $data = $this->cloner->cloneVar($var);
                    $this->eventDispatcher->dispatch('omouren.external_var_dump.event', new ExternalVarDumpEvent($data));
                };

                VarDumper::setHandler($handler);
                $handler($var);
            });
        }
    }
}
