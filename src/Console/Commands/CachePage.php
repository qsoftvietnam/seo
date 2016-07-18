<?php

namespace Qsoft\Seo\Console\Commands;

use File;
use Illuminate\Console\Command;
use QsoftClawer;
use Qsoft\Seo\Contracts\QsoftClawer;
use SoapBox\Formatter\Formatter;

class CachePage extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qsoft:cache:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache page from sitemap.xml';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xml = $this->getSitemap();

        $formatter = Formatter::make($xml, Formatter::XML);

        $result = $formatter->toArray();

        if (isset($result['url']) && count($result['url'])) {
            $cache = new QsoftCache;
            foreach ($result['url'] as $key => $value) {
                if ($cache->make($value['loc'])) {
                    $this->info($value['loc']);

                } else {
                    $this->error('Something went wrong!');
                }
            }
        }

    }

    private function getSitemap()
    {
        if (config('qsoft_seo.sitemap_type') == 1) {
            return File::get(config('qsoft_seo.sitemap_link'));
        } else {
            return false;
        }
    }

    private function cache($url)
    {
        # code...
        $path = config('qsoft_seo.cache_path');
        $file = $path . '/' . sha1($url);
        if (!File::exists($path)) {
            File::makeDirectory($path, null, true, true);
        }
        $clawer = new QsoftClawer;
        $content = $clawer->get($url);
        if (File::exists($file)) {
            File::delete($file);
        }
        File::put($file, $content);
        return $content;

    }
}
