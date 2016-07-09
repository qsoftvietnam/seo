<?php
/**
 * @Author: thedv
 * @Date:   2016-07-01 17:59:38
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-09 16:26:56
 */

return [
    /**
     * Phantomjs path
     */
    'phantom_path' => env('PHANTOMJS_PATH', base_path('bin/phantomjs.exe')),
    /**
     * Cache Path
     */
    'cache_path'   => storage_path('cache'),

    /**
     * example: http:example.com | Default base_host
     */
    'host'         => env('QSOFT_SEO_HOST', ''),

    /**
     * Html5 mode enable or disable
     */
    'html5'        => env('QSOFT_SEO_HTML5', false),

    /**
     * Time cache per request
     */
    'time_refresh' => env('SEO_TIME_REFRESH', 3600),
];
