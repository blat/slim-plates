<?php

namespace Slim\Views;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;

class PlatesExtension implements ExtensionInterface, MiddlewareInterface
{

    protected App $app;
    protected UriInterface $uri;

    /**
     * PlatesExtension constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->uri = $request->getUri();
        $this->app->getContainer()->get('view')->loadExtension($this);
        return $handler->handle($request);
    }

    /**
     * @param Engine $engine
     */
    public function register(Engine $engine)
    {
        foreach (['urlFor', 'fullUrlFor', 'isCurrentUrl', 'getCurrentUrl', 'getBasePath'] as $name) {
            $engine->registerFunction($name, [$this, $name]);
        }
    }

    /**
     * Get the url for a named route
     *
     * @param string                $routeName   Route name
     * @param array<string, string> $data        Route placeholders
     * @param array<string, string> $queryParams Query parameters
     *
     * @return string
     */
    public function urlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        return $this->app->getRouteCollector()->getRouteParser()->urlFor($routeName, $data, $queryParams);
    }

    /**
     * Get the full url for a named route
     *
     * @param string                $routeName   Route name
     * @param array<string, string> $data        Route placeholders
     * @param array<string, string> $queryParams Query parameters
     *
     * @return string
     */
    public function fullUrlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        return $this->app->getRouteCollector()->getRouteParser()->fullUrlFor($this->uri, $routeName, $data, $queryParams);
    }

    /**
     * @param string                $routeName Route name
     * @param array<string, string> $data      Route placeholders
     *
     * @return bool
     */
    public function isCurrentUrl(string $routeName, array $data = []): bool
    {
        $currentUrl = $this->getBasePath() . $this->uri->getPath();
        $result = $this->app->getRouteCollector()->getRouteParser()->urlFor($routeName, $data);

        return $result === $currentUrl;
    }

    /**
     * Get current path on given Uri
     *
     * @param bool $withQueryString
     *
     * @return string
     */
    public function getCurrentUrl(bool $withQueryString = false): string
    {
        $currentUrl = $this->getBasePath() . $this->uri->getPath();
        $query = $this->uri->getQuery();

        if ($withQueryString && !empty($query)) {
            $currentUrl .= '?' . $query;
        }

        return $currentUrl;
    }

    /**
     * Get the base path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->app->getBasePath();
    }

}
