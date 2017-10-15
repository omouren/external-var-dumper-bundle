<?php

namespace Omouren\ExternalVarDumperBundle\Services;

use Omouren\ExternalVarDumperBundle\Event\ExternalVarDumpEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class Dumper
{
    private $replaceDumper;

    private $eventDispatcher;

    private $cloner;

    private $dumper;

    public function __construct(EventDispatcherInterface $eventDispatcher, $replaceDumper)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->replaceDumper = $replaceDumper;
    }

    public function handleInit()
    {
        $this->cloner = new VarCloner();

        if (!$this->replaceDumper) {
            $this->dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();
        }

        VarDumper::setHandler(function($var) {
            $var = $this->cloner->cloneVar($var);

            $this->eventDispatcher->dispatch('omouren.external_var_dump.event', new ExternalVarDumpEvent($var));

            if (!$this->replaceDumper) {
                $this->dumper->dump($var);
            }
        });
    }
}
