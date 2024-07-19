<?php

namespace Omouren\ExternalVarDumperBundle\Services;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\ContextProvider\ContextProviderInterface;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Server\Connection as BaseConnection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class Connection extends BaseConnection
{
    private $host;
    private $contextProviders;
    private $descriptor;
    private $client;

    /**
     * @param string                     $host             The server host
     * @param ContextProviderInterface[] $contextProviders Context providers indexed by context name
     */
    public function __construct(string $host, array $contextProviders = [])
    {
        if (false === strpos($host, '://')) {
            $host = 'http://'.$host;
        }
        $host = str_replace('tcp://', 'http://', $host);

        $this->host = $host;
        $this->contextProviders = $contextProviders;
        $this->descriptor = new JsonDescriptor(new HtmlDumper());
    }

    public function setHttpClient(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getContextProviders(): array
    {
        return $this->contextProviders;
    }

    public function write(Data $data): bool
    {
        $context = ['timestamp' => microtime(true)];
        foreach ($this->contextProviders as $name => $provider) {
            $context[$name] = $provider->getContext();
        }
        $context = array_filter($context);
        $json = $this->descriptor->describe($data, $context);

        try {
            $this->client->request('POST', $this->host, [
                'body' => $json,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}
