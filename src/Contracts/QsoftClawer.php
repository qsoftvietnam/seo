<?php
/**
 * @Author: Duong The
 * @Date:   2016-07-15 15:41:06
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-15 16:31:06
 */

namespace Qsoft\Seo\Contracts;

use JonnyW\PhantomJs\Client;

class QsoftClawer
{
    /**
     * [$client description]
     * @var [type]
     */
    protected $client;

    /**
     * Init
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
     * Get page content from url
     * @param  $url string
     * @return string
     */
    public function get($url)
    {
        # code...
        $request  = $this->client->getMessageFactory()->createRequest($url, 'GET');
        $response = $this->client->getMessageFactory()->createResponse();
        $this->client->send($request, $response);
        return $response->getContent();
    }

}
