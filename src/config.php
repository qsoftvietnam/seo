<?php
/**
 * @Author: thedv
 * @Date:   2016-07-01 17:59:38
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-16 15:28:22
 */

return [
    /**
     * Phantomjs path
     * base_path('bin/phantomjs.exe') | window
     * base_path('bin/phantomjs') | linux
     * default: linux
     */
    'phantom_path' => env('PHANTOMJS_PATH', null),
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

    /**
     * 1: Localfile
     * 2: Url file
     */
    'sitemap_type' => 1,

    /**
     * Sitemap link by type
     */
    'sitemap_link' => public_path('sitemap.xml'),

    /**
     * Cron pattern
     */
    'cron'         => '* * * * * *',

    /**
     * Set the timezone
     */
    'time_zone'    => 'Asia/Ho_Chi_Minh',
];
