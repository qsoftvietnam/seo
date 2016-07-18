<?php
/**
 * @Author: Duong The
 * @Date:   2016-07-15 19:07:35
 * @Last Modified by:   Duong The
 * @Last Modified time: 2016-07-18 11:11:32
 */

namespace Qsoft\Seo\Contracts;

use File;
use Qsoft\Seo\Contracts\QsoftClawer;

class QsoftCache
{

    public function make($url)
    {
        $path = config('qsoft_seo.cache_path');
        $file = $path . '/' . sha1($url);
        if (!File::exists($path)) {
            File::makeDirectory($path, null, true, true);
        }
        $clawer  = new QsoftClawer;
        $content = $clawer->get($url);

        if (File::exists($file)) {
            File::delete($file);
        }
        File::put($file, $content);
        return $content;
    }
}
