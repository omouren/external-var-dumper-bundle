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

    public function getDump(Data $var) {
        $this->content = null;

        $this->dump($var);

        $this->content .= '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" onload="var h = this.parentNode.innerHTML, rx=/<script>(.*?)<\/script>/g, s; while (s = rx.exec(h)) {eval(s[1]);};" />';

        return array(
            'id' => $this->dumpId,
            'content' => $this->content
        );
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
