<?php
/**
 * @Author: Duong The
 * @Date:   2016-07-15 16:04:37
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-15 16:08:49
 */
namespace Qsoft\Seo\Support\Facades;

use Illuminate\Support\Facades\Facade;

class QsoftClawer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'qsoft_clawer';
    }
}