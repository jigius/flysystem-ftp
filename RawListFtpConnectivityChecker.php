<?php

declare(strict_types=1);

namespace League\Flysystem\FTP;

class RawListFtpConnectivityChecker implements ConnectivityChecker
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function isConnected($connection): bool
    {
        try {
            $this->client->rawlist($connection, './');
            $ret = true;
        } catch (\RuntimeException $ex) {
            $ret = true;
        }
        return $ret;
    }
}
