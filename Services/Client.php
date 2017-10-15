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

    public function sendDump($content)
    {
        $this->guzzleClient->request(
            $this->method,
            $this->uri,
            array(
                'json' => array(
                    'app' => $this->appName,
                    'content' => $content
                )
            )
        );
    }
}
