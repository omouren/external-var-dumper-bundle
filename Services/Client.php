<?php

namespace Omouren\ExternalVarDumperBundle\Services;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class Client
{
    private $appName;

    private $guzzleClient;

    private $uri;

    private $method;

    public function __construct($appName, $uri, $method)
    {
        $this->appName = $appName;
        $this->uri = $uri;
        $this->method = $method;

        $this->guzzleClient = new \GuzzleHttp\Client();
    }

    public function sendDump($dump, $source, \DateTime $datetime)
    {
        $this->guzzleClient->request(
            $this->method,
            $this->uri,
            array(
                'json' => array(
                    'app' => $this->appName,
                    'id' => $dump['id'],
                    'content' => $dump['content'],
                    'source' => $source,
                    'datetime' => $datetime->format(\DateTime::W3C)
                )
            )
        );
    }
}
