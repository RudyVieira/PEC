<?php

namespace App\Lib\Http;

class Request
{
    protected string $uri;
    protected string $path;
    protected string $method;
    protected array $slugs;
    protected array $urlParams;
    protected array $headers;
    protected string $payload;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->path = parse_url($this->uri, PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->slugs = [];
        $this->urlParams = $_GET;
        $this->headers = getallheaders();
        $this->payload = file_get_contents('php://input');
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function addSlug(string $key, string $value): self
    {
        $this->slugs[$key] = $value;
        return $this;
    }

    public function getSlugs(): array
    {
        return $this->slugs;
    }

    public function getSlug(string $key): string
    {
        return $this->slugs[$key] ?? '';
    }

    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): array
    {
        if ($this->method === 'POST') {
            return $_POST;
        }

        return [];
    }

    public function get(string $key) {
        return $this->getBody()[$key] ?? null;
    }
}