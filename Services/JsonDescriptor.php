<?php
namespace Omouren\ExternalVarDumperBundle\Services;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class JsonDescriptor
{
    private $dumper;

    public function __construct(HtmlDumper $dumper)
    {
        $this->dumper = $dumper;
    }

    public function describe(Data $data, array $context): string
    {
        $title = '-';
        if (isset($context['request'])) {
            $request = $context['request'];
            $controller = (string) $request['controller'];
            $title = sprintf('<span class="label label-danger">%s</span> <a href="%s">%s</a>', $request['method'], $uri = $request['uri'], $uri);
            $dedupIdentifier = $request['identifier'];
        } elseif (isset($context['cli'])) {
            $title = '<span class="label label-danger">$</span> '.$context['cli']['command_line'];
            $dedupIdentifier = $context['cli']['identifier'];
        } else {
            $dedupIdentifier = uniqid('', true);
        }

        $sourceDescription = '';
        if (isset($context['source'])) {
            $source = $context['source'];
            $projectDir = $source['project_dir'] ?? null;
            $sourceDescription = sprintf('%s on line %d', $source['name'], $source['line']);
            if (isset($source['file_link'])) {
                $sourceDescription = sprintf('<a href="%s">%s</a>', $source['file_link'], $sourceDescription);
            }
        }

        $isoDate = $this->extractDate($context, \DateTime::W3C);
        $tags = array_filter([
            'controller' => $controller ?? null,
            'project_dir' => $projectDir ?? null,
        ]);

        $json = [
            'id' => $dedupIdentifier,
            'title' => $title,
            'datetime' => $isoDate,
            'tags' => $tags,
            'source_description' => $sourceDescription,
            'content' => $this->dumper->dump($data, true).'<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" onload="var h = this.parentNode.innerHTML, rx=/<script>(.*?)<\/script>/g, s; while (s = rx.exec(h)) {eval(s[1]);};" />'

        ];

        return json_encode($json);
    }

    private function extractDate(array $context, string $format = 'r'): string
    {
        return date($format, (int) $context['timestamp']);
    }
}
