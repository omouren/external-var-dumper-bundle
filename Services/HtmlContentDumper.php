<?php

namespace Omouren\ExternalVarDumperBundle\Services;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class HtmlContentDumper extends HtmlDumper
{
    private $content;

    public function getContent(Data $var) {
        $this->content = null;

        $this->dump($var);

        return $this->content;
    }

    /**
     * Generic line dumper callback.
     *
     * @param string $line  The line to write
     * @param int    $depth The recursive depth in the dumped structure
     */
    protected function echoLine($line, $depth, $indentPad)
    {
        if (-1 !== $depth) {
            $this->content .= str_repeat($indentPad, $depth).$line."\n";
        }
    }
}
