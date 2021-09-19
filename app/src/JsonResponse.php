<?php
declare(strict_types=1);
namespace PhpClamav;
require_once __DIR__ . '/AbstractResponse.php';


class JsonResponse extends AbstractResponse {

    // $param $content Needs to be castable to array (Due to json formatting)
    public function __construct(Object $content, int $status = 200, array $headers = []) {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
        $this->setContentType('application/json');
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): AbstractResponse
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): JsonResponse
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): array
    {
        return (array) $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(Object $content): JsonResponse
    {
        $this->content = $content;
        return $this;
    }
}