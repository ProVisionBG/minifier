<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Minifier\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use ProVision\Administration\Administration;
use ProVision\Minifier\Minifier;
use ProVision\Minifier\MinifierContext;

class MinifierMiddleware
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        /*
         * Спиране на минифицирането в админа
         */
        if (class_exists('ProVision\Administration\Administration') && Administration::routeInAdministration()) {
            return $response;
        }

        if ($this->isAResponseObject($response) && $this->isAnHtmlResponse($response)) {
            $output = $response->getContent();

            $minifie = new Minifier(new MinifierContext($output));

            $response->setContent($minifie->get());
        }

        return $response;
    }

    /**
     * Check if the response is a usable response class.
     *
     * @param mixed $response
     *
     * @return bool
     */
    protected function isAResponseObject($response)
    {
        return is_object($response) && $response instanceof Response;
    }

    /**
     * Check if the content type header is html.
     *
     * @param \Illuminate\Http\Response $response
     *
     * @return bool
     */
    protected function isAnHtmlResponse(Response $response)
    {
        $type = $response->headers->get('Content-Type');
        return strtolower(strtok($type, ';')) === 'text/html';
    }
}