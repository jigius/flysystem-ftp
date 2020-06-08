<?php

declare(strict_types=1);

namespace League\Flysystem\FTP;

class NoopCommandConnectivityChecker implements ConnectivityChecker
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function isConnected($connection): bool
    {
        try {
            $response = $this->client->raw($connection, 'NOOP');
            $ret = (int) preg_replace('/\D/', '', implode('', $response)) === 200;
        } catch (\RuntimeException $ex) {
            $ret = false;
        }
        return $ret;
    }
}
