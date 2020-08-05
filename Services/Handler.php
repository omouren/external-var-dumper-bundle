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

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->htmlContentDumper = new HtmlContentDumper();
    }

    public function handleEvent(ExternalVarDumpEvent $event)
    {
        $this->client->sendDump($this->htmlContentDumper->getDump($event->getVar()), $event->getSource(), $event->getDatetime());
    }
}
