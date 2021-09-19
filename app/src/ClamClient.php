<?php
declare(strict_types=1);
/*
 * Class for handling the Socket-connections towards clamd.
 */
namespace PhpClamav;
require_once __DIR__ . '/AbstractSocket.php';
use PhpClamav\AbstractSocket;

class ClamClient extends AbstractSocket
{
    protected string $address = '127.0.0.1';
    protected int $port = 3310;
    // Reusable socket
    protected ?object $socket = null;
    private int $chunkSize = 2048;

    public function __construct(string $address = '127.0.0.1', int $port = 3310)
    {
        $this->address = $address;
        $this->port = $port;
    }

    /*
     * Sends a command to clamav.
     */
    private function send(string $data): bool
    {
        $this->getSocket();
        $result = socket_send($this->socket, $data, strlen($data),0);
        return false !== $result;
    }

    private function receive(): string
    {
        $buf = null;
        // Assume socket is already created
        socket_recv($this->socket, $buf, 20000, 0);
        return $buf;
    }

    private function getSocket(): bool
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
        $result = socket_connect($this->socket, $this->address, $this->port);
        return false !== $result;
    }

    public function ping(): bool
    {
        $cmd = 'PING';
        $this->send($cmd);
        $value = $this->receive();
        return trim($value) === 'PONG';
    }

    /*
     * The INSTREAM scanning functionality
     * ---- Protocol description (from the clamav documentation) ----
     *  The format of the chunk is: '<length><data>' where <length> is the size of the following data in bytes
     *  expressed as a 4 byte unsigned integer in network byte order and <data> is the actual chunk.
     *  Streaming is terminated by sending a zero-length chunk.
     *  Note: do not exceed StreamMaxLength as defined in clamd.conf, otherwise clamd will reply with INSTREAM
     *  size limit exceeded and close the connection.
     * ----------------------------------------------------------
     */
    public function scanStream($stream): string
    {
        $cmd = "zINSTREAM\0";
        $this->send($cmd);
        while (!feof($stream)) {
           $chunk = fread($stream, $this->chunkSize);
           $data = pack(sprintf("Na%d", strlen($chunk)), strlen($chunk), $chunk);
           socket_send($this->socket, $data, strlen($data), 0);
        }
        // Send empty data to conclude connection
        socket_send($this->socket, pack("Nx",0), 5, 0);
        return '' . trim(explode(':', $this->receive())[1]);
    }

    public function scanFile(string $file)
    {
        $this->send('SCAN' . $file);
        return $this->receive();
    }

    public function close(): void
    {
        socket_close($this->socket);
    }
}