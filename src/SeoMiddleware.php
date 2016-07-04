<?php

namespace Qsoft\Seo;

/**
 * @Author: thedv
 * @Date:   2016-07-01 18:11:49
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-04 11:09:32
 */

use Closure;
use File;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use JonnyW\PhantomJs\Client;

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
     * This token will be provided via the X-Prerender-Token header.
     *
     * @var string
     */
    private $response;

    public function __construct()
    {
        $this->client = Client::getInstance();
        $this->client->getEngine()->setPath(base_path('bin/phantomjs.exe'));

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
        $userAgent                   = strtolower($request->server->get('HTTP_USER_AGENT'));
        $bufferAgent                 = $request->server->get('X-BUFFERBOT');
        $requestUri                  = $request->getRequestUri();
        $referer                     = $request->headers->get('Referer');
        $isRequestingPrerenderedPage = false;
        if (!$userAgent) {
            return false;
        }

        if (!$request->isMethod('GET')) {
            return false;
        }

        // prerender if _escaped_fragment_ is in the query string
        if ($request->query->has('_escaped_fragment_')) {
            $isRequestingPrerenderedPage = true;
        }

        if ($bufferAgent) {
            $isRequestingPrerenderedPage = true;
        }

        if (!$isRequestingPrerenderedPage) {
            return false;
        }
        // Okay! Prerender please.
        return true;
    }

    private function getRenderPageResponse($request)
    {

        $build = $this->buildUrlRequest($request);
        try {
            $req = $this->client->getMessageFactory()->createRequest($build['url'], 'GET');
            $res = $this->client->getMessageFactory()->createResponse();
            $this->client->send($req, $res);
            $body = $this->cache($build['path'], $res->getContent());
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
     * @param  [type] $request [description]
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    private function cache($url, $content)
    {
        # code...
        $path = config('qsoft_seo.cache_path');
        $file = $path . '/' . sha1($url);
        if (!File::exists($path)) {
            File::makeDirectory($path, null, true, true);
        }
        if (File::exists($file)) {
            $lastMod = File::lastModified($file);
            $now     = time();
            $cc      = $now - $lastMod;
            if ($cc < config('qsoft_seo.time_refresh')) {
                return File::get($file);
            } else {
                File::put($file, $content);
                return $content;
            }

        } else {
            File::put($file, $content);
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
