<?php
/**
 * @Author: thedv
 * @Date:   2016-07-01 17:59:38
 * @Last Modified by:   thedv
 * @Last Modified time: 2016-07-04 10:05:38
 */

return [

    /**
     * Cache Path
     */
    'cache_path' => storage_path('cache'),

    /**
     * example: http:example.com | Default base_host
     */
    'host'       => env('QSOFT_SEO_HOST', ''),

    /**
     * Html5 mode enable or disable
     */
    'html5'      => env('QSOFT_SEO_HTML5', false),
];
