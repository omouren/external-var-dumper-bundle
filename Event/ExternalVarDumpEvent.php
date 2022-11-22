<?php

namespace Omouren\ExternalVarDumperBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\VarDumper\Cloner\Data;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class ExternalVarDumpEvent extends Event
{
    private $var;

    private $source;

    private $datetime;

    public function __construct(Data $var)
    {
        $this->var = $var;
        $this->source = $this->getSourceFromBackTrace();
        $this->datetime = new \DateTime();
    }

    public function getVar()
    {
        return $this->var;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    private function getSourceFromBackTrace()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 7);

        $file = $trace[0]['file'];
        $line = $trace[0]['line'];
        $name = false;

        for ($i = 1; $i < 7; ++$i) {
            if (isset($trace[$i]['class'], $trace[$i]['function'])
                && 'dump' === $trace[$i]['function']
                && 'Symfony\Component\VarDumper\VarDumper' === $trace[$i]['class']
            ) {
                $file = $trace[$i]['file'];
                $line = $trace[$i]['line'];

                while (++$i < 7) {
                    if (isset($trace[$i]['function'], $trace[$i]['file']) && empty($trace[$i]['class']) && 0 !== strpos($trace[$i]['function'], 'call_user_func')) {
                        $file = $trace[$i]['file'];
                        $line = $trace[$i]['line'];

                        break;
                    } elseif (isset($trace[$i]['object']) && $trace[$i]['object'] instanceof \Twig_Template) {
                        $info = $trace[$i]['object'];
                        $name = $info->getTemplateName();
                        $src = method_exists($info, 'getSource') ? $info->getSource() : $info->getEnvironment()->getLoader()->getSource($name);
                        $info = $info->getDebugInfo();
                        if (null !== $src && isset($info[$trace[$i - 1]['line']])) {
                            $file = false;
                            $line = $info[$trace[$i - 1]['line']];
                        }
                        break;
                    }
                }
                break;
            }
        }

        if (false === $name) {
            $name = str_replace('\\', '/', $file);
            $name = substr($name, strrpos($name, '/') + 1);
        }

        return array(
            'name' => $name !== false ? $name : null,
            'line' => $line
        );
    }
}
