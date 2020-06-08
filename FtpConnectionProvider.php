<?php

declare(strict_types=1);

namespace League\Flysystem\FTP;

use const FTP_USEPASVADDRESS;
use RuntimeException;

/**
 * Class FtpConnectionProvider
 * @package League\Flysystem\FTP
 */
class FtpConnectionProvider implements ConnectionProvider
{
    /**
     * @var Client
     */
    private $client;

    /**
     * FtpConnectionProvider constructor.
     * @param Client|null $client
     */
    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new FtpClient();
    }

    /**
     * @param FtpConnectionOptions $options
     * @return resource
     * @throws FtpConnectionException
     */
    public function createConnection(FtpConnectionOptions $options)
    {
        $connection = $this->createConnectionResource(
            $options->host(),
            $options->port(),
            $options->timeout(),
            $options->ssl()
        );

        try {
            $this->authenticate($options, $connection);
            $this->enableUtf8Mode($options, $connection);
            $this->ignorePassiveAddress($options, $connection);
            $this->makeConnectionPassive($options, $connection);
        } catch (FtpConnectionException $exception) {
            $this->client->close($connection);
            throw $exception;
        }
        return $connection;
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @param bool $ssl
     * @return resource
     * @throws UnableToConnectToFtpHost
     */
    private function createConnectionResource(string $host, int $port, int $timeout, bool $ssl)
    {
        try {
            $connection =
                $ssl ?
                    $this->client->sslConnect($host, $port, $timeout) :
                    $this->client->connect($host, $port, $timeout);

        } catch (RuntimeException $ex) {
            throw UnableToConnectToFtpHost::forHost($host, $port, $ssl);
        }
        return $connection;
    }

    /**
     * @param FtpConnectionOptions $options
     * @param resource $connection
     * @throws UnableToAuthenticate
     */
    private function authenticate(FtpConnectionOptions $options, $connection): void
    {
        try {
            $this->client->login($connection, $options->username(), $options->password());
        } catch (RuntimeException $ex) {
            throw new UnableToAuthenticate();
        }
    }

    /**
     * @param FtpConnectionOptions $options
     * @param resource $connection
     * @throws RuntimeException|UnableToEnableUtf8Mode
     */
    private function enableUtf8Mode(FtpConnectionOptions $options, $connection): void
    {
        if ( ! $options->utf8()) {
            return;
        }
        $response = $this->client->raw($connection, "OPTS UTF8 ON");
        if (substr($response[0] ?? '', 0, 3) !== '200') {
            throw new UnableToEnableUtf8Mode(
                'Could not set UTF-8 mode for connection: ' . $options->host() . '::' . $options->port()
            );
        }
    }

    /**
     * @param FtpConnectionOptions $options
     * @param resource $connection
     * @throws RuntimeException|UnableToSetFtpOption
     */
    private function ignorePassiveAddress(FtpConnectionOptions $options, $connection): void
    {
        $ignorePassiveAddress = $options->ignorePassiveAddress();

        if ( ! is_bool($ignorePassiveAddress) || ! defined('FTP_USEPASVADDRESS')) {
            return;
        }

        try {
            $this->client->setOption($connection, FTP_USEPASVADDRESS, !$ignorePassiveAddress);
        } catch (RuntimeException $ex) {
            throw UnableToSetFtpOption::whileSettingOption('FTP_USEPASVADDRESS');
        }
    }

    /**
     * @param FtpConnectionOptions $options
     * @param resource $connection
     * @throws UnableToMakeConnectionPassive
     */
    private function makeConnectionPassive(FtpConnectionOptions $options, $connection): void
    {
        try {
            $this->client->pasv($connection, $options->passive());
        } catch (RuntimeException $ex) {
            throw new UnableToMakeConnectionPassive(
                'Could not set passive mode for connection: ' . $options->host() . '::' . $options->port()
            );
        }
    }
}
