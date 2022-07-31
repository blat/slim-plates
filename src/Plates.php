<?php

namespace Slim\Views;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Plates
{
    protected Engine $engine;

    /**
     * @param ...mixed $settings
     */
    public function __construct(...$settings)
    {
        $this->engine = new Engine(...$settings);
    }

    /**
     * Access forward to Engine methods
     *
     * @param  string $name
     * @param  array  $args
     *
     * @return mixed
     */
    public function __call(string $name, array $args): mixed
    {
        return call_user_func_array([$this->engine, $name], $args);
    }

    /**
     * Output rendered template
     *
     * @param  ResponseInterface    $response
     * @param  string               $template Template pathname relative to templates directory
     * @param  array<string, mixed> $data Associative array of template variables
     *
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, string $template, array $data = []): ResponseInterface
    {
        $output = $this->engine->render($template, $data);
        $response->getBody()->write($output);
        return $response;
    }

}
