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

    private $events = array();


    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->htmlContentDumper = new HtmlContentDumper();
    }

    public function handleEvent(ExternalVarDumpEvent $event)
    {
        //if ('cli' === PHP_SAPI) {
            $this->client->sendDump($this->htmlContentDumper->getDump($event->getVar()), $event->getSource(), $event->getDatetime());
        //} else {
        //    $this->events[] = $event;
        //}
    }

    public function onKernelTerminate()
    {
        foreach ($this->events as $event) {
            $this->client->sendDump($this->htmlContentDumper->getDump($event->getVar()), $event->getSource(), $event->getDatetime());
        }
    }
}
