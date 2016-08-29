<?php

namespace Qsoftvn\Seo\Http\Middleware;

/**
 * @Author: thedv
 * @Date:   2016-07-01 18:11:49
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-18 16:01:55
 */

use Closure;
use File;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use JonnyW\PhantomJs\Client;
use Qsoftvn\Seo\Contracts\QsoftCache;

class SeoMiddleware
{

    /**
     * The application instance
     *
     * @var Application
     */
    private $app;
    /**
     * The Guzzle Client that sends GET requests to the prerender server
     *
     * @var Guzzle
     */
    private $client;

    /**
     * [$request description]
     * @var [type]
     */
    private $request;
    /**
     * This token will be provided via the X-Prerender-Token header.
     *
     * @var string
     */
    private $response;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->client = Client::getInstance();
        $phantomPath  = config('qsoft_seo.phantom_path');
        if ($phantomPath) {
            $this->client->getEngine()->setPath($phantomPath);
        }

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->shouldShowRenderPage($request)) {
            return $this->getRenderPageResponse($request);
        }
        return $next($request);
    }

    private function shouldShowRenderPage($request)
    {
        $userAgent                = strtolower($request->server->get('HTTP_USER_AGENT'));
        $bufferAgent              = $request->server->get('X-BUFFERBOT');
        $requestUri               = $request->getRequestUri();
        $referer                  = $request->headers->get('Referer');
        $isRequestingRenderedPage = false;
        if (!$userAgent) {
            return false;
        }

        if (!$request->isMethod('GET')) {
            return false;
        }

        // prerender if _escaped_fragment_ is in the query string
        if ($request->query->has('_escaped_fragment_')) {
            $isRequestingRenderedPage = true;
        }

        if ($bufferAgent) {
            $isRequestingRenderedPage = true;
        }

        if (!$isRequestingRenderedPage) {
            return false;
        }
        // Render
        return true;
    }

    private function getRenderPageResponse($request)
    {

        $build = $this->buildUrlRequest($request);
        try {
            $body = $this->cache($build['url']);
            return $this->buildResponse($body);

        } catch (RequestException $exception) {

            return null;
        }
    }

    /**
     * Convert a Guzzle Response to a Symfony Response
     *
     * @param ResponseInterface $prerenderedResponse
     * @return Response
     */
    private function buildResponse($content)
    {
        $status   = 200;
        $response = response($content, $status)->header('Content-Type', 'text/HTML');
        return $response;
    }

    /**
     * [cache description]
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    private function cache($url)
    {
        # code...
        $path = config('qsoft_seo.cache_path');
        $file = $path . '/' . sha1($url);

        if (File::exists($file)) {
            return File::get($file);
        } else {
            $cache   = new QsoftCache;
            $content = $cache->make($url);
            return $content;
        }

    }

    /**
     * [buildUrlRequest description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    private function buildUrlRequest($request)
    {
        $path = $request->_escaped_fragment_;
        if (!$path) {
            $path = '/' . $request->Path();
        }

        $protocol = $request->isSecure() ? 'https' : 'http';

        $host = $request->getHost();

        if (!config('qsoft_seo.host')) {
            $baseHost = $protocol . '://' . $host;
        } else {
            $baseHost = config('qsoft_seo.host');
        }
        if (config('qsoft_seo.html5')) {
            $prefix = '';
        } else {
            $prefix = '/#!/';
        }

        $urlRequest = $baseHost . str_replace('//', '/', $prefix . $path);
        return [
            'url'  => $urlRequest,
            'path' => $path,
        ];
    }
}
