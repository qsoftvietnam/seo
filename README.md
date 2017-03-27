## Laravel SEO package

## How to install

It is recommended that you use Composer to install PHP PhantomJS. First, add the following to your project’s `composer.json` file:
```php
    "scripts": {
        "post-install-cmd": [
            ...
            "PhantomInstaller\\Installer::installPhantomJS"
        ],
        "post-update-cmd": [
            ...
            "PhantomInstaller\\Installer::installPhantomJS"
        ]
    }
```

This will ensure the latest version of PhantomJS is installed for your system, in your bin folder. If you haven’t defined your bin folder in your `composer.json`, add the path:

```php
    "config": {
        ...
        "bin-dir": "bin"
    }
```

Install composer package

```shell
	composer require qsoftvn/seo:dev-master
```

### Provider
```php
	Qsoftvn\Seo\SeoServiceProvider::class,
```

### Alias
```php
    'QsoftClawer' => Qsoftvn\Seo\Support\Facades\QsoftClawer::class,
    'QsoftCache'  => Qsoftvn\Seo\Support\Facades\QsoftCache::class,
```

### Publish config

```shell
	php artisan vendor:publish --provider="Qsoftvn\Seo\SeoServiceProvider"
```

### Update `phantom_path` from qsoft_seo.php

```php
    * base_path('bin/phantomjs.exe') | window
    * base_path('bin/phantomjs') | linux

    'phantom_path' => env('PHANTOMJS_PATH', null),
```