<?php
declare(strict_types=1);
namespace PhpClamav;
use PhpClamav\Exceptions\HttpContentTypeNotSupportedException;

class Request
{
    private string $method;
    private string $remote_address;
    private string $req_uri;
    private int $req_time;
    private ?array $query_string;
    private ?string $content_type;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->remote_address = $_SERVER['REMOTE_ADDR'];
        $this->req_uri = $_SERVER['REQUEST_URI'];
        $this->req_time  = $_SERVER['REQUEST_TIME'];
        $this->query_string = $_SERVER['QUERY_STRING'] ?? null;
        $this->content_type = $_SERVER['CONTENT_TYPE'] ?? null;
    }

    /**
     * @return mixed|string
     */
    public function getMethod(): mixed
    {
        return $this->method;
    }

    /**
     * @return mixed|string
     */
    public function getRemoteAddress(): mixed
    {
        return $this->remote_address;
    }

    /**
     * @return mixed|string
     */
    public function getReqUri(): mixed
    {
        return $this->req_uri;
    }

    /**
     * @return int|mixed
     */
    public function getReqTime(): mixed
    {
        return $this->req_time;
    }

    /**
     * @return array|mixed|null
     */
    public function getQueryString(): mixed
    {
        return $this->query_string;
    }

    /**
     * @return mixed|string|null
     */
    public function getContentType(): mixed
    {
        return $this->content_type;
    }

    /**
     * @return array|string[]
     */
    public function getAllowedContentTypes(): array
    {
        return $this->allowedContentTypes;
    }

}