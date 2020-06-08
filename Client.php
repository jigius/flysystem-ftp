<?php

declare(strict_types=1);

namespace League\Flysystem\FTP;

use RuntimeException;

interface Client
{
    /**
     * @param resource $stream
     * @param string $directory
     * @throws RuntimeException
     */
    public function chdir($stream, string $directory): void;

    /**
     * @param $stream
     * @param int $mode
     * @param string $filename
     * @return int
     * @throws RuntimeException
     */
    public function chmod($stream , int $mode , string $filename ): int;

    /**
     * @param $stream
     * @throws RuntimeException
     */
    public function close($stream): void;

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @return resource
     * @throws RuntimeException
     */
    public function connect(string $host, int $port = 21, int $timeout = 90);

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @return resource
     * @throws RuntimeException
     */
    public function sslConnect(string $host, int $port = 21, int $timeout = 90);

    /**
     * @param resource $stream
     * @param string $path
     * @throws RuntimeException
     */
    public function delete($stream, string $path): void;

    /**
     * @param resource $stream
     * @param resource $handle
     * @param string $remote
     * @param int $mode
     * @param int $resumePos
     * @throws RuntimeException
     */
    public function fget($stream, $handle, string $remote, int $mode = FTP_BINARY, int $resumePos = 0): void;

    /**
     * @param resource $stream
     * @param string $remote
     * @param resource $handle
     * @param int $mode
     * @param int $startPos
     * @throws RuntimeException
     */
    public function fput($stream, string $remote, $handle, int $mode = FTP_BINARY, int $startPos = 0): void;

    /**
     * @param resource $stream
     * @param string $username
     * @param string $password
     * @throws RuntimeException
     */
    public function login($stream, string $username, string $password): void;

    /**
     * @param resource $stream
     * @param string $remote
     * @return int
     * @throws RuntimeException
     */
    public function mdtm($stream, string $remote): int;

    /**
     * @param resource $stream
     * @param string $directory
     * @return string
     * @throws RuntimeException
     */
    public function mkdir($stream, string $directory): string;

    /**
     * @param resource $stream
     * @param bool $pasv
     * @throws RuntimeException
     */
    public function pasv($stream, bool $pasv): void;

    /**
     * @param resource $stream
     * @param string $command
     * @return array
     * @throws RuntimeException
     */
    public function raw($stream , string $command): array;

    /**
     * @param resource $stream
     * @param string $directory
     * @param bool $recursive
     * @return array
     * @throws RuntimeException
     */
    public function rawlist($stream, string $directory, bool $recursive = false): array;

    /**
     * @param resource $stream
     * @param string $old
     * @param string $new
     * @throws RuntimeException
     */
    public function rename($stream, string $old, string $new): void;

    /**
     * @param resource $stream
     * @param string $directory
     * @throws RuntimeException
     */
    public function rmdir($stream, string $directory): void;

    /**
     * @param resource $stream
     * @param int $option
     * @param mixed $value
     * @throws RuntimeException
     */
    public function setOption($stream, int $option, $value): void;

    /**
     * @param resource $stream
     * @param string $remote
     * @return int
     * @throws RuntimeException
     */
    public function size($stream, string $remote): int;
}
