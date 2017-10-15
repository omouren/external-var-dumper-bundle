<?php

namespace Omouren\ExternalVarDumperBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\VarDumper\Cloner\Data;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class ExternalVarDumpEvent extends Event
{
    private $var;

    public function __construct(Data $var)
    {
        $this->var = $var;
    }

    public function getVar()
    {
        return $this->var;
    }
}
