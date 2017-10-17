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
    private $originalDumperHandler;

    private $handler;

    public function boot()
    {
        /**
         * @var $container ContainerInterface
         */
        $container = $this->container;

        if ($container->getParameter('kernel.debug')) {
            $this->originalDumperHandler = VarDumper::setHandler(function($var) use ($container) {
                $replaceDumper = $container->getParameter('omouren_external_var_dumper.replace_dumper');
                $cloner = $container->get('var_dumper.cloner');
                $eventDispatcher = $container->get('event_dispatcher');

                $handler = function ($var) use ($replaceDumper, $cloner, $eventDispatcher) {
                    $data = $cloner->cloneVar($var);
                    $eventDispatcher->dispatch('omouren.external_var_dump.event', new ExternalVarDumpEvent($data));

                    if (!$replaceDumper) {
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
