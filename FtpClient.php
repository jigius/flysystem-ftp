<?php

declare(strict_types=1);

namespace League\Flysystem\FTP;

use RuntimeException;

/**
 * Class FtpClient
 * @package League\Flysystem\FTP
 */
class FtpClient implements Client
{
    /**
     * FtpClient constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param resource $stream
     * @param string $directory
     * @throws RuntimeException
     */
    public function chdir($stream, string $directory): void
    {
        $ret = @ftp_chdir($stream, $directory);
        if ($ret === false) {
            throw new RuntimeException("with directory=`{$directory}`");
        }
    }

    /**
     * @param $stream
     * @param int $mode
     * @param string $filename
     * @return int
     * @throws RuntimeException
     */
    public function chmod($stream, int $mode, string $filename): int
    {
        $mode = @ftp_chmod($stream, $mode, $filename);
        if ($mode === false) {
            throw new RuntimeException("with mode=`{$mode}`, filename=`{$filename}`");
        }
        return $mode;
    }

    /**
     * @param $stream
     * @throws RuntimeException
     */
    public function close($stream): void
    {
        $ret = @ftp_close($stream);
        if ($ret === false) {
            throw new RuntimeException();
        }
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @return false|resource
     */
    public function connect(string $host, int $port = 21, int $timeout = 90)
    {
        $resource = @ftp_connect($host, $port, $timeout);
        if ($resource === false) {
            throw new RuntimeException("with host=`{$host}`, port=`{$port}`, timeout=`{$timeout}`");
        }
        return $resource;
    }

    /**
     * @param resource $stream
     * @param string $path
     * @throws RuntimeException
     */
    public function delete($stream, string $path): void
    {
        $ret = @ftp_delete($stream, $path);
        if ($ret === false) {
            throw new RuntimeException("with path=`{$path}`");
        }
    }

    /**
     * @param resource $stream
     * @param resource $handle
     * @param string $remote
     * @param int $mode
     * @param int $resumePos
     * @throws RuntimeException
     */
    public function fget($stream, $handle, string $remote, int $mode = FTP_BINARY, int $resumePos = 0): void
    {
        $ret = @ftp_fget($stream, $handle, $remote, $mode, $resumePos);
        if ($ret === false) {
            throw
                new RuntimeException(
                    "with remote=`{$remote}`, mode=`{$mode}`, resumePos=`{$resumePos}`"
                );
        }
    }

    /**
     * @param resource $stream
     * @param string $remote
     * @param resource $handle
     * @param int $mode
     * @param int $startPos
     * @throws RuntimeException
     */
    public function fput($stream, string $remote, $handle, int $mode = FTP_BINARY, int $startPos = 0): void
    {
        $ret = @ftp_fput($stream, $remote, $handle, $mode, $startPos);
        if ($ret === false) {
            throw
                new RuntimeException(
                    "with remote=`{$remote}`, mode=`{$mode}`, resumePos=`{$startPos}`"
                );
        }
    }

    /**
     * @param resource $stream
     * @param string $username
     * @param string $password
     * @throws RuntimeException
     */
    public function login($stream, string $username, string $password): void
    {
        $ret = @ftp_login($stream, $username, $password);
        if ($ret === false) {
            throw new RuntimeException();
        }
    }

    /**
     * @param resource $stream
     * @param string $remote
     * @return int
     * @throws RuntimeException
     */
    public function mdtm($stream, string $remote): int
    {
        $ts = @ftp_mdtm($stream, $remote);
        if ($ts === false) {
            throw new RuntimeException("with remote=`{$remote}`");
        }
        return $ts;
    }

    /**
     * @param resource $stream
     * @param string $directory
     * @return string
     * @throws RuntimeException
     */
    public function mkdir($stream, string $directory): string
    {
        $dir = @ftp_mkdir($stream, $directory);
        if (empty($dir)) {
            throw new RuntimeException("with directory=`{$directory}`");
        }
        dump($dir);
        return $dir;
    }

    /**
     * @param resource $stream
     * @param bool $pasv
     * @throws RuntimeException
     */
    public function pasv($stream, bool $pasv): void
    {
        $ret = @ftp_pasv($stream, $pasv);
        if ($ret === false) {
            throw new RuntimeException("with pasv=`{$pasv}`");
        }
    }

    /**
     * @param resource $stream
     * @param string $command
     * @return array
     * @throws RuntimeException
     */
    public function raw($stream, string $command): array
    {
        $ret = @ftp_raw($stream, $command);
        if ($ret === false) {
            throw new RuntimeException("with command=`{$command}`");
        }
        return $ret;
    }

    /**
     * @param resource $stream
     * @param string $directory
     * @param bool $recursive
     * @return array
     * @throws RuntimeException
     */
    public function rawlist($stream, string $directory, bool $recursive = false): array
    {
        $ret = @ftp_rawlist($stream, $directory, $recursive);
        if ($ret === false) {
            throw
                new RuntimeException(
                    sprintf("with directory=`%s`, recursive=`%s`", $directory, $recursive? "true": "false")
                );
        }
        return $ret;
    }

    /**
     * @param resource $stream
     * @param string $old
     * @param string $new
     * @throws RuntimeException
     */
    public function rename($stream, string $old, string $new): void
    {
        $ret = @ftp_rename($stream, $old, $new);
        if ($ret === false) {
            throw new RuntimeException("with old=`{$old}`, new=`{$new}`");
        }
    }

    /**
     * @param resource $stream
     * @param string $directory
     * @throws RuntimeException
     */
    public function rmdir($stream, string $directory): void
    {
        $ret = @ftp_rmdir($stream, $directory);
        if ($ret === false) {
            throw new RuntimeException("with directory=`{$directory}`");
        }
    }

    /**
     * @param resource $stream
     * @param int $option
     * @param mixed $value
     * @throws RuntimeException
     */
    public function setOption($stream, int $option, $value): void
    {
        $ret = @ftp_set_option($stream, $option, $value);
        if ($ret === false) {
            throw new RuntimeException("with option=`{$option}`, value=`{$value}`");
        }
    }

    /**
     * @param resource $stream
     * @param string $remote
     * @return int
     * @throws RuntimeException
     */
    public function size($stream, string $remote): int
    {
        $size = @ftp_size($stream, $remote);
        if ($size === false) {
            throw new RuntimeException("with remote=`{$remote}`");
        }
        return $size;
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @return false|resource
     */
    public function sslConnect(string $host, int $port = 21, int $timeout = 90)
    {
        $resource = @ftp_ssl_connect($host, $port, $timeout);
        if ($resource === false) {
            throw new RuntimeException("with host=`{$host}`, port=`{$port}`, timeout=`{$timeout}`");
        }
        return $resource;
    }
}