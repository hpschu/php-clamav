<?php
declare(strict_types=1);
namespace PhpClamav;

abstract class AbstractSocket
{
    protected string $address;
    protected int $port;
    protected ?object $socket;

    /*
     * @return void
     */
    abstract function close(): void;
}