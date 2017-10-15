<?php

namespace Omouren\ExternalVarDumperBundle\Services;

use Omouren\ExternalVarDumperBundle\Event\ExternalVarDumpEvent;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class Handler
{
    private $client;

    private $htmlContentDumper;

    private $vars = array();

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->htmlContentDumper = new HtmlContentDumper();
    }

    public function handleEvent(ExternalVarDumpEvent $event)
    {
        $var = $event->getVar();

        if ('cli' === PHP_SAPI) {
            $this->client->sendDump($this->htmlContentDumper->getContent($var));
        } else {
            $this->vars[] = $var;
        }
    }

    public function onKernelTerminate()
    {
        foreach ($this->vars as $var) {
            $this->client->sendDump($this->htmlContentDumper->getContent($var));
        }
    }
}
