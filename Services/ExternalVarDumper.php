<?php

namespace Omouren\ExternalVarDumperBundle\Services;

use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;
use Omouren\ExternalVarDumperBundle\Event\ExternalVarDumpEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class Client
{
    private $eventDispatcher;

    private $replaceDumper;

    private $cloner;

    private $kernelDebug;

    private $originalDumperHandler;

    private $handler;

    public function __construct(EventDispatcherInterface $eventDispatcher, $kernelDebug, $cloner, $replaceDumper)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->kernelDebug = $kernelDebug;
        $this->cloner = $cloner;
        $this->replaceDumper = $replaceDumper;
    }

    public function handleInit()
    {
        if ($this->kernelDebug) {
            $this->originalDumperHandler = VarDumper::setHandler(function($var) {
                $handler = function ($var) {
                    $data = $this->cloner->cloneVar($var);
                    $this->eventDispatcher->dispatch('omouren.external_var_dump.event', new ExternalVarDumpEvent($data));

                    if (!$this->replaceDumper) {
                        if ($this->originalDumperHandler) {
                            call_user_func($this->originalDumperHandler, $var);
                            VarDumper::setHandler($this->handler);
                        } else {
                            $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();
                            $dumper->dump($data);
                        }
                    }
                };

                $this->handler = VarDumper::setHandler($handler);
                $handler($var);
            });
        }
    }
}
